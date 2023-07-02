<?php

namespace LightningPrism\Tests;

use Dotenv\Dotenv;
use LightningPrism\LightningPrism;
use LightningPrism\LightningPrismSettings;
use PHPUnit\Framework\TestCase;
use UtxoOne\LndPhp\Responses\Lightning\SendResponse;

final class LightningPrismTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testItCanZapPrism(): void
    {
        $settings = [
            'utxo@testnet.nodeless.io' => 10,
            'nostr@testnet.nodeless.io' => 40,
            'testing@testnet.nodeless.io' => 50,
        ];

        $prismSettings = new LightningPrismSettings($settings);

        $lightningPrism = new LightningPrism(
            settings: $prismSettings,
            amount: 2371,
            host: $this->host,
            port: $this->port,
            macaroon: $this->macaroon,
            tlsCertificate: $this->tlsCertificate,
        );

        $response = $lightningPrism->zap();

        $this->assertIsArray($response);
        foreach ($response as $sendResponse) {
            $this->assertInstanceOf(SendResponse::class, $sendResponse);
        }
    }
}
