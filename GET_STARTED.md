# Get Started with LifePlus PHP SDK

## Installation

```bash
composer require lifeplus/sdk
```

## Basic Usage

```php
<?php

require_once 'vendor/autoload.php';

use LifePlus\SDK\LifePlusClient;

// Create client
$client = new LifePlusClient('https://api.lifeplusbd.com/api/v2');

// Partner API (server-to-server)
$client->setPartnerCredentials('partner_123', 'lpk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Login
$session = $client->login('01712345678', 'password');
echo "Logged in as: " . $session->getData()->getUser()->getName() . "\n";

// Search products
$products = $client->products()->listProducts(
    searchKey: 'paracetamol',
    perPage: 10
);

echo "Found " . count($products) . " products\n";
foreach ($products as $product) {
    echo "- {$product->getName()}: BDT {$product->getPrice()}\n";
}
```

## Run the Demo

```bash
cd vendor/lifeplus/sdk/examples
php demo.php
```

## Documentation

- **README.md** - Complete documentation
- **QUICKSTART.md** - 5-minute quick start
- **API Reference** - Full API documentation

## Support

- **Mamun**: mamun@lifeplusbd.com / +880 1913705269
- **Sagor**: sagor@lifeplusbd.com / +880 1681408185
