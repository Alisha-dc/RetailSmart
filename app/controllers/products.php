<?php
require_once __DIR__ . '/../models/Product.php';


// Validate product information
function validate_product(
    string $name,
    string $category,
    string $price,
    string $originalStock,
    string $remainingStock = ''
): array {
    $errors = [];

    if ($name === '') {
        $errors[] = 'Product name is required.';
    }

    if ($category === '') {
        $errors[] = 'Category is required.';
    }

    if (!is_numeric($price) || (float) $price < 0) {
        $errors[] = 'Price must be a valid positive number.';
    }

    if (filter_var($originalStock, FILTER_VALIDATE_INT) === false || (int) $originalStock < 0) {
        $errors[] = 'Original stock must be a valid whole number.';
    }

    if ($remainingStock !== '' && (filter_var($remainingStock, FILTER_VALIDATE_INT) === false || (int) $remainingStock < 0)) {
        $errors[] = 'Remaining stock must be a valid whole number.';
    }

    if ($remainingStock !== '' && (int) $remainingStock > (int) $originalStock) {
        $errors[] = 'Remaining stock cannot be greater than original stock.';
    }

    return $errors;
}


// Add product
function create_product(
    string $name,
    string $description,
    string $category,
    string $price,
    string $originalStock,
    string $remainingStock = '',
    string $imageUrl = ''
): array {
    $errors = validate_product(
        $name,
        $category,
        $price,
        $originalStock,
        $remainingStock
    );

    if (!empty($errors)) {
        return $errors;
    }

    $remaining = $remainingStock === '' ? (int) $originalStock : (int) $remainingStock;
    $imageUrl = trim($imageUrl) !== '' ? trim($imageUrl) : '/RetailSmart/public/assets/images/product-placeholder.svg';

    Product::create(
        $name,
        $description,
        $category,
        (float) $price,
        (int) $originalStock,
        $remaining,
        $imageUrl
    );

    log_action(
        'Product Created',
        "Product: $name"
    );

    return [];
}

// Update product
function update_product(
    int $id,
    string $name,
    string $description,
    string $category,
    string $price,
    string $originalStock,
    string $remainingStock = '',
    string $imageUrl = ''
): array {
    $errors = validate_product(
        $name,
        $category,
        $price,
        $originalStock,
        $remainingStock
    );

    if (!Product::find($id)) {
        $errors[] = 'Product does not exist.';
    }

    if (!empty($errors)) {
        return $errors;
    }

    $remaining = $remainingStock === '' ? (int) $originalStock : (int) $remainingStock;
    $imageUrl = trim($imageUrl) !== '' ? trim($imageUrl) : '/RetailSmart/public/assets/images/product-placeholder.svg';

    Product::update(
        $id,
        $name,
        $description,
        $category,
        (float) $price,
        (int) $originalStock,
        $remaining,
        $imageUrl
    );

    log_action(
        'Product Updated',
        "Product ID: $id"
    );

    return [];
}

// Delete product
function delete_product(int $id): bool {
    $result = Product::delete($id);

    if ($result) {
        log_action(
            'Product Deleted',
            "Product ID: $id"
        );
    }

    return $result;
}