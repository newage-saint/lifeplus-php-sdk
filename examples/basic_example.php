<?php

require_once __DIR__ . '/../vendor/autoload.php';

use LifePlus\SDK\Client;
use LifePlus\SDK\Exception\ApiException;

// Initialize the SDK client with your API key
$client = new Client([
    'api_key' => 'lpak_test_xxx...',
    'debug' => true,
]);

try {
    // Example 1: List products
    echo "=== Listing Products ===\n";
    $products = $client->products()->list([
        'page' => 1,
        'per_page' => 10,
    ]);
    echo "Found " . count($products) . " products\n\n";

    // Example 2: Get user profile
    echo "=== Getting User Profile ===\n";
    $profile = $client->auth()->getProfile();
    echo "User: {$profile->name} ({$profile->email})\n\n";

    // Example 3: Add item to cart
    echo "=== Adding to Cart ===\n";
    $cartItem = $client->cart()->addItem([
        'product_id' => 123,
        'quantity' => 2,
    ]);
    echo "Added to cart: {$cartItem->id}\n\n";

    // Example 4: Get cart contents
    echo "=== Getting Cart ===\n";
    $cart = $client->cart()->get();
    echo "Cart total: {$cart->total}\n";
    echo "Items: " . count($cart->items) . "\n\n";

    // Example 5: Place order
    echo "=== Placing Order ===\n";
    $order = $client->orders()->create([
        'cart_id' => $cart->id,
        'address_id' => 'addr_456',
        'payment_method' => 'cash_on_delivery',
    ]);
    echo "Order placed: {$order->id}\n";
    echo "Status: {$order->status}\n\n";

    echo "✅ All examples completed successfully!\n";

} catch (ApiException $e) {
    echo "❌ API Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    if ($e->getErrors()) {
        echo "Validation Errors:\n";
        foreach ($e->getErrors() as $field => $messages) {
            echo "  $field: " . implode(', ', $messages) . "\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
