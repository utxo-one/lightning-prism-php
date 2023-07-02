# Lightning Prisms (PHP)

This library allows you to easily split a lightning payment to multiple lightning addresses.

## Installation

```sh
composer require utxo-one/lightning-prism-php
```

## Usage

```php
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
```

## Run Tests

You must setup a .env file in tests/Feature. See `.env.example` to set it up.

```sh
./vendor/bin/pest pest
```
