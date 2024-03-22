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
```
