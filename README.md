# Lightning Prisms (PHP)

This library allows you to easily split a lightning payment to multiple lightning addresses.

## Installation

```sh
composer require utxo-one/lightning-prism-php
```

## Usage

```php
use LightningPrism\LightningPrism;
use LightningPrism\LightningPrismSettings;

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

### Response

```php
array(3) {
  ["utxo@testnet.nodeless.io"]=>
  object(UtxoOne\LndPhp\Responses\Lightning\SendResponse)#307 (1) {
    ["data":"UtxoOne\LndPhp\Responses\Lightning\SendResponse":private]=>
    array(4) {
      ["payment_error"]=>
      string(0) ""
      ["payment_preimage"]=>
      string(44) "3KKpToqROgTCoOun1xnEvdr7QrzQKKJ05Kh3WOK0yVE="
      ["payment_route"]=>
      array(6) {
        ["total_time_lock"]=>
        int(2440088)
        ["total_fees"]=>
        string(1) "0"
        ["total_amt"]=>
        string(3) "237"
        ["hops"]=>
        array(1) {
          [0]=>
          array(13) {
            ["chan_id"]=>
            string(19) "2655259008430833665"
            ["chan_capacity"]=>
            string(7) "5000000"
            ["amt_to_forward"]=>
            string(3) "237"
            ["fee"]=>
            string(1) "0"
            ["expiry"]=>
            int(2440088)
            ["amt_to_forward_msat"]=>
            string(6) "237000"
            ["fee_msat"]=>
            string(1) "0"
            ["pub_key"]=>
            string(66) "038802f80e3e1ca7ea80e4c5951fa29a08f274209195de9fe5d47017afca36ecc5"
            ["tlv_payload"]=>
            bool(true)
            ["mpp_record"]=>
            array(2) {
              ["payment_addr"]=>
              string(44) "47GEVx8PDiy1mcRzMjz+F34o168o8gCxDrZQObqZ6No="
              ["total_amt_msat"]=>
              string(6) "237000"
            }
            ["amp_record"]=>
            NULL
            ["custom_records"]=>
            array(0) {
            }
            ["metadata"]=>
            string(0) ""
          }
        }
        ["total_fees_msat"]=>
        string(1) "0"
        ["total_amt_msat"]=>
        string(6) "237000"
      }
      ["payment_hash"]=>
      string(44) "GjYPtDnFDjYweITH1Ce+mKQ0xmB9CI7F6AB+6m6hgM8="
    }
  }
  ["nostr@testnet.nodeless.io"]=>
  object(UtxoOne\LndPhp\Responses\Lightning\SendResponse)#99 (1) {
    ["data":"UtxoOne\LndPhp\Responses\Lightning\SendResponse":private]=>
    array(4) {
      ["payment_error"]=>
      string(0) ""
      ["payment_preimage"]=>
      string(44) "ziXkRdHXDxgHzrB4Q/dQCIlz6bfWrYx611NrolBuQJI="
      ["payment_route"]=>
      array(6) {
        ["total_time_lock"]=>
        int(2440088)
        ["total_fees"]=>
        string(1) "0"
        ["total_amt"]=>
        string(3) "948"
        ["hops"]=>
        array(1) {
          [0]=>
          array(13) {
            ["chan_id"]=>
            string(19) "2655259008430833665"
            ["chan_capacity"]=>
            string(7) "5000000"
            ["amt_to_forward"]=>
            string(3) "948"
            ["fee"]=>
            string(1) "0"
            ["expiry"]=>
            int(2440088)
            ["amt_to_forward_msat"]=>
            string(6) "948000"
            ["fee_msat"]=>
            string(1) "0"
            ["pub_key"]=>
            string(66) "038802f80e3e1ca7ea80e4c5951fa29a08f274209195de9fe5d47017afca36ecc5"
            ["tlv_payload"]=>
            bool(true)
            ["mpp_record"]=>
            array(2) {
              ["payment_addr"]=>
              string(44) "6qS6dtHj3QQhWIwm1W+2WFz4i97WTwS+3sdZ8SRgiQ4="
              ["total_amt_msat"]=>
              string(6) "948000"
            }
            ["amp_record"]=>
            NULL
            ["custom_records"]=>
            array(0) {
            }
            ["metadata"]=>
            string(0) ""
          }
        }
        ["total_fees_msat"]=>
        string(1) "0"
        ["total_amt_msat"]=>
        string(6) "948000"
      }
      ["payment_hash"]=>
      string(44) "kNTTR9wpZQH7m5DXHtPV1GCdCB+nzSJnv56vEcSgMkc="
    }
  }
  ["testing@testnet.nodeless.io"]=>
  object(UtxoOne\LndPhp\Responses\Lightning\SendResponse)#596 (1) {
    ["data":"UtxoOne\LndPhp\Responses\Lightning\SendResponse":private]=>
    array(4) {
      ["payment_error"]=>
      string(0) ""
      ["payment_preimage"]=>
      string(44) "UlqmGjHVBYOfwqJ8uRi3D7O4e3YN9hFjTOupd7jWIU0="
      ["payment_route"]=>
      array(6) {
        ["total_time_lock"]=>
        int(2440088)
        ["total_fees"]=>
        string(1) "0"
        ["total_amt"]=>
        string(4) "1186"
        ["hops"]=>
        array(1) {
          [0]=>
          array(13) {
            ["chan_id"]=>
            string(19) "2655259008430833665"
            ["chan_capacity"]=>
            string(7) "5000000"
            ["amt_to_forward"]=>
            string(4) "1186"
            ["fee"]=>
            string(1) "0"
            ["expiry"]=>
            int(2440088)
            ["amt_to_forward_msat"]=>
            string(7) "1186000"
            ["fee_msat"]=>
            string(1) "0"
            ["pub_key"]=>
            string(66) "038802f80e3e1ca7ea80e4c5951fa29a08f274209195de9fe5d47017afca36ecc5"
            ["tlv_payload"]=>
            bool(true)
            ["mpp_record"]=>
            array(2) {
              ["payment_addr"]=>
              string(44) "UmjuFmoLxVLAI37l68GLQG4bXclmN5H0kBi9LtrxCeY="
              ["total_amt_msat"]=>
              string(7) "1186000"
            }
            ["amp_record"]=>
            NULL
            ["custom_records"]=>
            array(0) {
            }
            ["metadata"]=>
            string(0) ""
          }
        }
        ["total_fees_msat"]=>
        string(1) "0"
        ["total_amt_msat"]=>
        string(7) "1186000"
      }
      ["payment_hash"]=>
      string(44) "Os2zAL8a4lPnjxDgQrxee11Gz3fCXqwzooQo+01qcyo="
    }
  }
}
```

## Run Tests

You must setup a .env file in tests/Feature. See `.env.example` to set it up.

```sh
./vendor/bin/pest pest
```
