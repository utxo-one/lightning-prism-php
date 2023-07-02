<?php

namespace LightningPrism;

use UtxoOne\LndPhp\Responses\Lightning\SendResponse;
use UtxoOne\LndPhp\Services\LightningService;

class LightningPrism
{
    public function __construct(
        private LightningPrismSettings $settings,
        private int $amount,
        private string $host,
        private string $port,
        private string $macaroon,
        private string $tlsCertificate,
        private bool $isTestnet = false,
    ) {
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
}
