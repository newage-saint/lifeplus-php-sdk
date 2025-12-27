<?php

require_once __DIR__ . '/../vendor/autoload.php';

use LifePlus\SDK\LifePlusClient;
use LifePlus\SDK\ApiException;
use LifePlus\SDK\Helpers;

echo "=== LifePlus PHP SDK Demo ===\n\n";

try {
    // Create client
    $client = new LifePlusClient('https://api.lifeplusbd.com/api/v2');
    
    // Example 1: List Products
    echo "1. Listing products...\n";
    $products = $client->products()->listProducts(
        page: 1,
        perPage: 5
    );
    
    echo "✓ Found " . count($products) . " products:\n";
    foreach ($products as $i => $product) {
        $name = Helpers::stringOrEmpty($product->getName());
        $price = Helpers::floatOrZero($product->getPrice());
        echo "  " . ($i + 1) . ". {$name} - " . Helpers::formatPrice($price) . "\n";
    }
    echo "\n";
    
    // Example 2: Search Products
    echo "2. Searching for products...\n";
    $searchResults = $client->products()->listProducts(
        searchKey: 'paracetamol',
        perPage: 3
    );
    
    echo "✓ Search found " . count($searchResults) . " results:\n";
    foreach ($searchResults as $i => $product) {
        $name = Helpers::stringOrEmpty($product->getName());
        $price = Helpers::floatOrZero($product->getPrice());
        echo "  " . ($i + 1) . ". {$name} - " . Helpers::formatPrice($price) . "\n";
    }
    echo "\n";
    
    // Example 3: Get Categories
    echo "3. Getting categories...\n";
    $categories = $client->products()->getLifestyleCategories();
    
    echo "✓ Found " . count($categories) . " categories\n";
    $count = 0;
    foreach ($categories as $i => $category) {
        if ($count >= 5) break;
        echo "  " . ($i + 1) . ". " . Helpers::stringOrEmpty($category->getName()) . "\n";
        $count++;
    }
    echo "\n";
    
    // Example 4: List Doctors
    echo "4. Listing doctors...\n";
    $doctors = $client->doctors()->listDoctors(
        page: 1,
        perPage: 3
    );
    
    echo "✓ Found " . count($doctors) . " doctors:\n";
    foreach ($doctors as $i => $doctor) {
        $name = Helpers::stringOrEmpty($doctor->getName());
        $specialty = Helpers::stringOrEmpty($doctor->getSpecialtyName());
        $fee = Helpers::floatOrZero($doctor->getConsultationFee());
        echo "  " . ($i + 1) . ". Dr. {$name} - {$specialty} (" . Helpers::formatPrice($fee) . ")\n";
    }
    echo "\n";
    
    // Example 5: Get Specialties
    echo "5. Getting specialties...\n";
    $specialties = $client->lookup()->getSpecialties();
    
    echo "✓ Found " . count($specialties) . " specialties\n";
    $count = 0;
    foreach ($specialties as $i => $specialty) {
        if ($count >= 5) break;
        echo "  " . ($i + 1) . ". " . Helpers::stringOrEmpty($specialty->getName()) . "\n";
        $count++;
    }
    echo "\n";
    
    // Example 6: List Hospitals
    echo "6. Listing hospitals...\n";
    $hospitals = $client->hospitals()->listHospitals(
        page: 1,
        perPage: 3
    );
    
    echo "✓ Found " . count($hospitals) . " hospitals:\n";
    foreach ($hospitals as $i => $hospital) {
        $name = Helpers::stringOrEmpty($hospital->getName());
        $address = Helpers::stringOrEmpty($hospital->getAddress());
        echo "  " . ($i + 1) . ". {$name}\n";
        echo "     Location: {$address}\n";
    }
    echo "\n";
    
    // Example 7: List Packages
    echo "7. Listing healthcare packages...\n";
    $packages = $client->packages()->listPackages(
        page: 1,
        perPage: 3
    );
    
    echo "✓ Found " . count($packages) . " packages:\n";
    foreach ($packages as $i => $package) {
        $name = Helpers::stringOrEmpty($package->getName());
        $price = Helpers::floatOrZero($package->getPrice());
        echo "  " . ($i + 1) . ". {$name} - " . Helpers::formatPrice($price) . "\n";
    }
    echo "\n";
    
    echo "✓ Demo completed!\n\n";
    echo "Note: Login with real credentials to test authenticated endpoints.\n";
    echo "Contact: mamun@lifeplusbd.com / +880 1913705269\n";
    
} catch (ApiException $e) {
    echo "❌ API Error: {$e->getMessage()}\n";
    echo "Status Code: {$e->getCode()}\n";
    if ($e->getResponseBody()) {
        echo "Response: {$e->getResponseBody()}\n";
    }
    exit(1);
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}
