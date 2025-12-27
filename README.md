# LifePlus Healthcare Platform - PHP SDK

Official PHP SDK for the LifePlus Healthcare Platform API. This SDK provides easy access to Bangladesh's leading digital healthcare platform.

[![Latest Version](https://img.shields.io/packagist/v/lifeplus/sdk)](https://packagist.org/packages/lifeplus/sdk)
[![PHP Version](https://img.shields.io/packagist/php-v/lifeplus/sdk)](https://packagist.org/packages/lifeplus/sdk)
[![License](https://img.shields.io/packagist/l/lifeplus/sdk)](LICENSE)

## Features

- 🏥 Complete healthcare platform integration
- 💊 Medicine ordering and delivery
- 👨‍⚕️ Doctor consultations and appointments
- 🏨 Hospital services and bookings
- 🚑 Ambulance services
- 🧬 Diagnostic and lab test bookings
- 📦 Healthcare package management
- 🔐 Secure authentication with automatic token management
- 🎯 Type-safe API with full IDE support
- 📝 Comprehensive documentation

## Requirements

- PHP 8.1 or higher
- Composer
- GuzzleHTTP 7.0+

## Installation

```bash
composer require lifeplus/sdk
```

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use LifePlus\SDK\LifePlusClient;

// Create client
$client = new LifePlusClient('https://api.lifeplusbd.com/api/v2');

// Partner API (server-to-server)
// Use this when integrating as a B2B partner with X-API-Key + X-Partner-ID
$client->setPartnerCredentials('partner_123', 'lpk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Login
$session = $client->login('01712345678', 'password');
echo "Logged in as: " . $session->getData()->getUser()->getName() . "\n";

// Search for products
$products = $client->products()->listProducts(
    searchKey: 'paracetamol',
    perPage: 10
);

foreach ($products as $product) {
    echo "{$product->getName()} - BDT {$product->getPrice()}\n";
}
```

## Documentation

### Authentication

```php
// Login with phone and password
$session = $client->login('01712345678', 'password');

// Partner API (server-to-server)
// Use this when integrating as a B2B partner with X-API-Key + X-Partner-ID
$client->setPartnerCredentials('partner_123', 'lpk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Verify phone with OTP
$session = $client->verifyPhone('01712345678', '123456');

// Check authentication status
if ($client->isAuthenticated()) {
    echo "User is logged in\n";
}

// Get current user
$user = $client->getSession()->getData()->getUser();

// Logout
$client->logout();
```

### Products & Medicine

```php
// List products with pagination
$products = $client->products()->listProducts(
    page: 1,
    perPage: 20,
    searchKey: 'paracetamol'
);

// Get product details
$product = $client->products()->getProduct($productId);

// Get categories
$categories = $client->products()->getLifestyleCategories();

// Get manufacturers
$manufacturers = $client->products()->getManufacturers();
```

### Shopping Cart

```php
// Add item to cart
$cart = $client->cart()->addToCart([
    'product_id' => 123,
    'quantity' => 2
]);

// Update cart item
$cart = $client->cart()->updateCartItem($cartItemId, [
    'quantity' => 3
]);

// Get cart
$cart = $client->cart()->getCart();

// Clear cart
$client->cart()->clearCart();
```

### Orders

```php
// Create order
$order = $client->orders()->createOrder([
    'address_id' => 1,
    'payment_method' => 'cod',
    'items' => [
        ['product_id' => 123, 'quantity' => 2]
    ]
]);

// List user orders
$orders = $client->orders()->listOrders(
    page: 1,
    perPage: 10
);

// Get order details
$order = $client->orders()->getOrder($orderId);

// Track order
$tracking = $client->orders()->trackOrder($orderId);
```

### Doctors & Appointments

```php
// List doctors
$doctors = $client->doctors()->listDoctors(
    specialtyId: 5,
    page: 1,
    perPage: 10
);

// Get doctor details
$doctor = $client->doctors()->getDoctor($doctorId);

// Get doctor's available slots
$slots = $client->doctors()->getDoctorSlots($doctorId, [
    'date' => '2024-01-15'
]);

// Book appointment
$appointment = $client->appointments()->bookAppointment([
    'doctor_id' => 123,
    'appointment_date' => '2024-01-15',
    'slot_id' => 456,
    'payment_method' => 'online'
]);

// List appointments
$appointments = $client->appointments()->listAppointments();

// Get appointment details
$appointment = $client->appointments()->getAppointment($appointmentId);
```

### Hospitals

```php
// List hospitals
$hospitals = $client->hospitals()->listHospitals(
    page: 1,
    perPage: 10
);

// Get hospital details
$hospital = $client->hospitals()->getHospital($hospitalId);

// Search hospitals by location
$hospitals = $client->hospitals()->listHospitals(
    searchKey: 'Dhaka'
);
```

### Healthcare Packages

```php
// List packages
$packages = $client->packages()->listPackages(
    page: 1,
    perPage: 10
);

// Get package details
$package = $client->packages()->getPackage($packageId);

// Book package
$booking = $client->packages()->bookPackage([
    'package_id' => 123,
    'preferred_date' => '2024-01-15',
    'payment_method' => 'online'
]);
```

### Ambulance Services

```php
// Get ambulance pricing
$pricing = $client->ambulance()->getAmbulancePricing([
    'from_latitude' => 23.8103,
    'from_longitude' => 90.4125,
    'to_latitude' => 23.7809,
    'to_longitude' => 90.2792
]);

// Book ambulance
$booking = $client->ambulance()->placeAmbulanceOrder([
    'from_address' => 'Gulshan, Dhaka',
    'to_address' => 'Mirpur, Dhaka',
    'patient_name' => 'John Doe',
    'contact_number' => '01712345678',
    'emergency_type' => 'medical'
]);
```

### Home Sample Collection

```php
// List available tests
$tests = $client->homeSample()->listTests();

// Book home sample collection
$booking = $client->homeSample()->bookHomeSample([
    'test_ids' => [1, 2, 3],
    'collection_date' => '2024-01-15',
    'collection_time' => '10:00',
    'address_id' => 1
]);
```

### Telemedicine

```php
// Get video call history
$history = $client->telemedicine()->getVideoCallHistory();

// Get call details
$call = $client->telemedicine()->getVideoCallDetails($callId);
```

### Address Management

```php
// List addresses
$addresses = $client->addresses()->listAddresses();

// Add address
$address = $client->addresses()->addAddress([
    'address_line' => '123 Main Street',
    'area' => 'Gulshan',
    'city' => 'Dhaka',
    'postal_code' => '1212',
    'phone' => '01712345678',
    'is_default' => true
]);

// Update address
$address = $client->addresses()->updateAddress($addressId, [
    'address_line' => '456 New Street'
]);

// Delete address
$client->addresses()->deleteAddress($addressId);
```

### Lookup Data

```php
// Get specialties
$specialties = $client->lookup()->getSpecialties();

// Get countries
$countries = $client->lookup()->getCountries();
```

## Helper Functions

The SDK includes helper functions for common operations:

```php
use LifePlus\SDK\Helpers;

// Format phone number
$phone = Helpers::formatPhone('+880 1712-345678'); // Returns: 01712345678

// Format price
$price = Helpers::formatPrice(199.99); // Returns: BDT 199.99

// Safe null handling
$name = Helpers::stringOrEmpty($user->getName());
$count = Helpers::intOrZero($product->getStock());
$price = Helpers::floatOrZero($product->getPrice());
```

## Error Handling

```php
use LifePlus\SDK\ApiException;

try {
    $products = $client->products()->listProducts();
} catch (ApiException $e) {
    echo "API Error: " . $e->getMessage() . "\n";
    echo "Status Code: " . $e->getCode() . "\n";
    echo "Response Body: " . $e->getResponseBody() . "\n";
}
```

## Configuration

```php
// Create client with custom options
$client = new LifePlusClient('https://api.lifeplusbd.com/api/v2', [
    'debug' => true,
    'timeout' => 30
]);

// Set token manually (for stored sessions)
$client->setAccessToken('your-stored-token');
```

## Laravel Integration

Add to `config/services.php`:

```php
'lifeplus' => [
    'base_url' => env('LIFEPLUS_API_URL', 'https://api.lifeplusbd.com/api/v2'),
],
```

Create a service provider:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LifePlus\SDK\LifePlusClient;

class LifePlusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(LifePlusClient::class, function ($app) {
            return new LifePlusClient(
                config('services.lifeplus.base_url')
            );
        });
    }
}
```

Use in controllers:

```php
use LifePlus\SDK\LifePlusClient;

class ProductController extends Controller
{
    public function __construct(
        private LifePlusClient $lifeplus
    ) {}

    public function index()
    {
        $products = $this->lifeplus->products()->listProducts();
        return view('products.index', compact('products'));
    }
}
```

## Testing

```bash
# Run tests
composer test

# Run with coverage
composer test:coverage
```

## Examples

See the `examples/` directory for complete working examples:

- `demo.php` - Basic usage demonstration
- `laravel-example.php` - Laravel integration example

## Support

- **Email**: mamun@lifeplusbd.com
- **Phone**: +880 1913705269
- **Sagor**: sagor@lifeplusbd.com / +880 1681408185
- **Website**: https://lifeplusbd.com

## License

This SDK is proprietary software owned by LifePlus Healthcare Platform.

## Changelog

### Version 3.1.0
- Initial release with full API coverage
- Complete authentication flow
- All healthcare services integrated
- Laravel support
- Comprehensive documentation

---

Made with ❤️ by LifePlus Healthcare Platform
