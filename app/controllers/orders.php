<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

// Add item to cart
function add_to_cart(
    int $productId,
    int $quantity
): array {
    $errors = [];

    if ($quantity < 1) {
        $errors[] = 'Quantity must be at least 1';
        return $errors;
    }

    if ($productId <= 0) {
        $errors[] = 'Invalid product.';
        return $errors;
    }

    $product = Product::find($productId);

    if (!$product) {
        $errors[] = 'Product not found.';
        return $errors;
    }

    $availableStock = (int) ($product['remaining_stock'] ?? $product['stock'] ?? 0);

    if ($availableStock <= 0) {
        $errors[] = 'Out of stock.';
        return $errors;
    }

    if ($quantity > $availableStock) {
        $errors[] = 'Only ' . $availableStock . ' item(s) left in stock.';
        return $errors;
    }

    $cart = $_SESSION['cart'] ?? [];
    $existingQuantity = (int) ($cart[$productId] ?? 0);
    $newQuantity = $existingQuantity + $quantity;

    if ($newQuantity > $availableStock) {
        $errors[] = 'You already have ' . $existingQuantity . ' in cart. Only ' . $availableStock . ' item(s) left in stock.';
        return $errors;
    }

    $cart[$productId] = $newQuantity;
    $_SESSION['cart'] = $cart;

    return [];
}

// Remove item
function remove_from_cart(
    int $productId
): void {
    unset($_SESSION['cart'][$productId]);
}


// Empty cart
function clear_cart(): void
{
    $_SESSION['cart'] = [];
}


// Create customer order
function create_order(): array
{
    $user = current_user();

    if (!$user) {
        return [
            'success' => false,
            'message' => 'Please login first.'
        ];
    }

    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        return [
            'success' => false,
            'message' => 'Your cart is empty.'
        ];
    }

    try {
        $orderId = Order::createFromCart((int) $user['id'], $cart);

        // Clear cart after successful order
        $_SESSION['cart'] = [];

        log_action(
            'Order Created',
            "Order ID: $orderId"
        );

        return [
            'success' => true,
            'message' => 'Order created successfully.',
            'order_id' => $orderId
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}