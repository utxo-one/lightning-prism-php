<?php

namespace LightningPrism\Tests;

use Dotenv\Dotenv;
use LightningPrism\LightningPrismSettings;
use PHPUnit\Framework\TestCase;

final class LightningPrismSettingsTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testItCanSetPrismSettings(): void
    {
        $settings = [
            'utxo@nodeless.io' => 50,
            'zaps@pay.utxo.one' => 50,
        ];

        $prismSettings = new LightningPrismSettings($settings);

        $this->assertIsArray($prismSettings->getSettings());
    }
}
