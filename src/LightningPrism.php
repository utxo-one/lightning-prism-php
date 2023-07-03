<?php

namespace LightningPrism;

use UtxoOne\LndPhp\Responses\Lightning\SendResponse;
use UtxoOne\LndPhp\Services\LightningService;

class LightningPrism
{
    public function __construct(
        private $settings,
        private int $amount,
        private string $host,
        private string $port,
        private string $macaroon,
        private string $tlsCertificate,
        private bool $isTestnet = false,
    ) {
        $this->settings = new LightningPrismSettings($settings);
    }

    public function zap(): array
    {
        $responses = [];
        foreach($this->settings->getSettings() as $lightningAddress => $percentage) {
            $satsAmount = round($this->amount * ($percentage / 100));
            $responses[$lightningAddress] = $this->payLightningAddress($satsAmount, $lightningAddress);
        }

        return $responses;
    }

    private function payLightningAddress(int $satsAmount, string $lightningAddress): SendResponse
    {
        $paymentRequest = $this->lnurlCallback($lightningAddress, $satsAmount)->pr;

        if ($this->getSatAmountFromInvoice($paymentRequest) !== $satsAmount) {
            throw new \Exception('Amount in invoice does not match requested amount. expected: ' . $satsAmount . ' actual: ' . $this->getSatAmountFromInvoice($paymentRequest));
        }

        try {
            return $this->sendLightningPayment($paymentRequest);
        } catch (\Exception $e) {
            throw new \Exception('Failed to send lightning payment: ' . $e->getMessage());
        }
    }

    private function lnurlCallback(string $lightningAddress, int $satsAmount): object
    {
        $domain = explode('@', $lightningAddress)[1];
        $identifier = explode('@', $lightningAddress)[0];
        $milisatsAmount = $satsAmount * 1000;

        $http = new \GuzzleHttp\Client();
        $callbackResponse = $http->get('http://' . $domain . '/.well-known/lnurlp/' . $identifier);

        $callbackJson = json_decode($callbackResponse->getBody());

        if ($callbackResponse->getStatusCode() !== 200) {
            throw new \Exception('Did not get a 200 response from lnurl endpoint for address: ' . $lightningAddress);
        }

        if ($callbackJson->tag !== 'payRequest') {
            throw new \Exception('Did not get a payRequest tag from lnurl endpoint for address: ' . $lightningAddress);
        }

        if ($milisatsAmount < $callbackJson->minSendable || $milisatsAmount > $callbackJson->maxSendable) {
            throw new \Exception('Amount is out of range. Min: ' . $callbackJson->minSendable . ' Max: ' . $callbackJson->maxSendable . ' Amount: ' . $milisatsAmount);
        }

        $paymentRequestResponse = $http->get($callbackJson->callback . '?amount=' . $milisatsAmount);

        return json_decode($paymentRequestResponse->getBody());
    }

    private function sendLightningPayment(string $paymentRequest): SendResponse
    {
        $lightningService = new LightningService(
            $this->host,
            $this->port,
            $this->macaroon,
            $this->tlsCertificate,
        );

        return $lightningService->sendPaymentSync(
            paymentRequest: $paymentRequest,
            allowSelfPayment: true,
        );
    }

    private function getSatAmountFromInvoice(string $invoice): int
    {
        $hrp = $this->extractHrpFromInvoice($invoice);
        return $this->hrpToSat($hrp, false);
    }

    private function extractHrpFromInvoice($invoice)
    {
        $pattern = '/(lnbc|lntb)(\d+[a-z])1/';
        preg_match($pattern, $invoice, $matches);

        if (!isset($matches[2])) {
            throw new \Exception('Invalid invoice format. Unable to extract HRP.');
        }

        return $matches[2];
    }

    private function hrpToSat(string $hrpString, bool $outputString): int
    {
        $divisor = '';
        $value = '';

        if (preg_match('/^[munp]$/', substr($hrpString, -1))) {
            $divisor = substr($hrpString, -1);
            $value = substr($hrpString, 0, -1);
        } elseif (preg_match('/^[^munp0-9]$/', substr($hrpString, -1))) {
            throw new \Exception('Not a valid multiplier for the amount');
        } else {
            $value = $hrpString;
        }

        if (!preg_match('/^\d+$/', $value)) {
            throw new \Exception('Not a valid human readable amount');
        }

        $valueBN = bcpow($value, '1');

        $SATS_PER_BTC = '100000000';
        $DIVISORS = ['m' => '1000', 'u' => '1000000', 'n' => '1000000000', 'p' => '1000000000000'];

        $millisatoshisBN = $divisor
            ? bcdiv(bcmul($valueBN, $SATS_PER_BTC), $DIVISORS[$divisor], 0)
            : bcmul($valueBN, $SATS_PER_BTC);

        $MAX_MILLISATS = '2100000000000000';

        if (($divisor === 'p' && bcmod($valueBN, '10') != 0) || bccomp($millisatoshisBN, $MAX_MILLISATS) > 0) {
            throw new \Exception('Amount is outside of valid range');
        }

        return $outputString ? $millisatoshisBN : (int)$millisatoshisBN;
    }
}
