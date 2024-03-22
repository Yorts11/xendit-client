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

### Creating Payout
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

    dd($client->createPayout($params));
```
The reponse will contain a payout details
```json
    {
        "id": "disb-48ee0751-abb3-4df5-85c7-24bcd3fabdec",
        "amount": 1000,
        "channel_code": "PH_BDO",
        "currency": "PHP",
        "description": "Sample Payout",
        "reference_id": "sample-payout-123",
        "status": "ACCEPTED",
        "created": "2024-03-22T03:24:52.647Z",
        "updated": "2024-03-22T03:24:52.647Z",
        "estimated_arrival_time": "2024-03-22T03:39:52.646Z",
        "business_id": "65f28790f16055763085bbdd",
        "channel_properties": {
            "account_number": "123456789",
            "account_holder_name": "Caster Troy Ventura"
        }
    }

```
### Retrieving Payout by Reference ID
```php
    $reference_id = 'sample-payout-123';
    // will return all payout based on your reference id
    dd($client->getPayoutReferenceId($reference_id));
```
### Retrieving Payout by ID
```php
    $id = 'disb-48ee0751-abb3-4df5-85c7-24bcd3fabdec';
    // will return all payout based on xendit transaction id
    dd($client->getPayoutId($id));
```
