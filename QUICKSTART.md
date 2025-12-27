# LifePlus PHP SDK - Quick Start Guide

Get up and running with the LifePlus Healthcare Platform API in 5 minutes.

## Installation

```bash
composer require lifeplus/sdk
```

## 1. Initialize the Client

```php
<?php
require_once 'vendor/autoload.php';

use LifePlus\SDK\LifePlusClient;

$client = new LifePlusClient('https://api.lifeplusbd.com/api/v2');
```

## 2. Authentication

```php
// Partner API (server-to-server)
$client->setPartnerCredentials('partner_123', 'lpk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Login with phone and password
$session = $client->login('01712345678', 'password');
$user = $session->getData()->getUser();
echo "Welcome, {$user->getName()}!\n";

// Or verify with OTP
$session = $client->verifyPhone('01712345678', '123456');
```

## 3. Browse Products

```php
// Search for medicine
$products = $client->products()->listProducts(
    searchKey: 'paracetamol',
    perPage: 10
);

foreach ($products as $product) {
    echo "{$product->getName()} - BDT {$product->getPrice()}\n";
}
```

## 4. Manage Cart

```php
// Add to cart
$cart = $client->cart()->addToCart([
    'product_id' => 123,
    'quantity' => 2
]);

// View cart
$cart = $client->cart()->getCart();
echo "Cart total: BDT {$cart->getTotal()}\n";
```

## 5. Place Order

```php
// Create order
$order = $client->orders()->createOrder([
    'address_id' => 1,
    'payment_method' => 'cod'
]);

echo "Order placed! Order ID: {$order->getId()}\n";
```

## 6. Find Doctors

```php
// List doctors
$doctors = $client->doctors()->listDoctors(
    specialtyId: 5,
    perPage: 5
);

foreach ($doctors as $doctor) {
    echo "Dr. {$doctor->getName()} - {$doctor->getSpecialtyName()}\n";
    echo "Fee: BDT {$doctor->getConsultationFee()}\n\n";
}
```

## 7. Book Appointment

```php
// Get available slots
$slots = $client->doctors()->getDoctorSlots($doctorId, [
    'date' => '2024-01-15'
]);

// Book appointment
$appointment = $client->appointments()->bookAppointment([
    'doctor_id' => $doctorId,
    'appointment_date' => '2024-01-15',
    'slot_id' => $slots[0]->getId(),
    'payment_method' => 'online'
]);

echo "Appointment booked! ID: {$appointment->getId()}\n";
```

## 8. Healthcare Packages

```php
// Browse packages
$packages = $client->packages()->listPackages();

foreach ($packages as $package) {
    echo "{$package->getName()} - BDT {$package->getPrice()}\n";
}

// Book package
$booking = $client->packages()->bookPackage([
    'package_id' => 123,
    'preferred_date' => '2024-01-15',
    'payment_method' => 'online'
]);
```

## 9. Ambulance Service

```php
// Get pricing
$pricing = $client->ambulance()->getAmbulancePricing([
    'from_latitude' => 23.8103,
    'from_longitude' => 90.4125,
    'to_latitude' => 23.7809,
    'to_longitude' => 90.2792
]);

echo "Estimated fare: BDT {$pricing->getFare()}\n";

// Book ambulance
$booking = $client->ambulance()->placeAmbulanceOrder([
    'from_address' => 'Gulshan, Dhaka',
    'to_address' => 'Mirpur, Dhaka',
    'patient_name' => 'John Doe',
    'contact_number' => '01712345678'
]);
```

## 10. Error Handling

```php
use LifePlus\SDK\ApiException;

try {
    $products = $client->products()->listProducts();
} catch (ApiException $e) {
    echo "Error: {$e->getMessage()}\n";
    echo "Status: {$e->getCode()}\n";
}
```

## Complete Example

```php
<?php

require_once 'vendor/autoload.php';

use LifePlus\SDK\LifePlusClient;
use LifePlus\SDK\ApiException;

try {
    // Initialize
    $client = new LifePlusClient('https://api.lifeplusbd.com/api/v2');
    
    // Login
    $session = $client->login('01712345678', 'password');
    echo "✓ Logged in as: {$session->getData()->getUser()->getName()}\n\n";
    
    // Search products
    $products = $client->products()->listProducts(
        searchKey: 'paracetamol',
        perPage: 5
    );
    echo "✓ Found " . count($products) . " products\n";
    
    // Add to cart
    if (count($products) > 0) {
        $cart = $client->cart()->addToCart([
            'product_id' => $products[0]->getId(),
            'quantity' => 2
        ]);
        echo "✓ Added to cart\n";
    }
    
    // View cart
    $cart = $client->cart()->getCart();
    echo "✓ Cart total: BDT {$cart->getTotal()}\n\n";
    
    // List doctors
    $doctors = $client->doctors()->listDoctors(perPage: 3);
    echo "✓ Found " . count($doctors) . " doctors\n";
    
    // Logout
    $client->logout();
    echo "✓ Logged out\n";
    
    echo "\n✅ Quick start completed successfully!\n";
    
} catch (ApiException $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}
```

## Next Steps

- Read the [Complete Documentation](README.md)
- Explore [API Reference](docs/)
- Check out [Examples](examples/)
- Join our developer community

## Support

- **Email**: mamun@lifeplusbd.com
- **Phone**: +880 1913705269
- **Website**: https://lifeplusbd.com

---

Happy coding! 🚀
