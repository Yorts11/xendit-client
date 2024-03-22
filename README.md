# XENDIT CLIENT
Xendit Client is a PHP wrapper for the XENDIT API

# Table of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)

## Installation
```sh
composer require yorts/xendit
```
## Basic Usage

### Create Payout
```php
<?php
  require_once( 'vendor/autoload.php' );
  use Yorts\Xendit\XenditPayout;
  $client = new XenditPayout('{YOUR_API_KEY}');
  $params = [
    'reference_id' => 'your reference id',
    'channel_code' => 'channel code you want',
    'channel_properties' => [
        'account_holder_name' => 'your holder name',
        'account_number' => 'your account number',
    ],
    'amount' => (float)$request->amount,
    'currency' => 'your currency',
    'description' => 'Description you want',
    "receipt_notification": {          //receipt_notification is optional
          "email_to": [
              "somebody@xendit.co"
          ],
          "email_cc": [
              "somebody@xendit.co"
          ]
      },
    "metadata": {                      //meta data is optional
         "lotto_outlet": 'your outlet' 
      }
    ];
```

