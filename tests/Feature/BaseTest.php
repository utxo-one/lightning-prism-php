<?php

namespace LightningPrism\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    protected string $host;
    protected string $port;
    protected string $macaroon;
    protected string $tlsCertificate;

    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();

        if (!isset($_ENV['LND_HOST'], $_ENV['LND_PORT'], $_ENV['LND_MACAROON_HEX'], $_ENV['LND_TLS_CERT'])) {
            throw new \Exception('Missing .env variables');
        }
    }

    protected function setUp(): void
    {
        $this->host = $_ENV['LND_HOST'];
        $this->port = $_ENV['LND_PORT'];
        $this->macaroon = $_ENV['LND_MACAROON_HEX'];
        $this->tlsCertificate = $_ENV['LND_TLS_CERT'];
    }

    public function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }
}
