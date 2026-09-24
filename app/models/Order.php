<?php

class Order {
    // Get all orders belonging to one customer
    public static function forUser(int $userId): array {
        $stmt = db()->prepare("
            SELECT *
            FROM orders
            WHERE user_id = ?
            ORDER BY id DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find one order
    public static function find(int $id): ?array {
        $stmt = db()->prepare("
            SELECT orders.*, users.name, users.email
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.id = ?
        ");

        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    // Get order items
    public static function items(int $orderId): array {
        $stmt = db()->prepare("
            SELECT order_items.*, products.name
            FROM order_items
            JOIN products ON order_items.product_id = products.id
            WHERE order_items.order_id = ?
        ");

        $stmt->execute([$orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all orders for admin
    public static function all(): array {
        $stmt = db()->query("
            SELECT orders.*, users.name, users.email
            FROM orders
            JOIN users ON orders.user_id = users.id
            ORDER BY orders.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create an order from the shopping cart
    public static function createFromCart(int $userId, array $cart): int {
        if (empty($cart)) {
            throw new Exception('Your cart is empty.');
        }

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $total = 0;
            $items = [];

            foreach ($cart as $productId => $quantity) {
                $productId = (int) $productId;
                $quantity = (int) $quantity;

                if ($quantity <= 0) {
                    throw new Exception('Invalid quantity.');
                }

                $stmt = $pdo->prepare("
                    SELECT *
                    FROM products
                    WHERE id = ?
                    FOR UPDATE
                ");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception('Product not found.');
                }

                $availableStock = (int) ($product['remaining_stock'] ?? $product['stock'] ?? 0);

                if ($quantity > $availableStock) {
                    throw new Exception('Not enough stock for ' . $product['name'] . '. Only ' . $availableStock . ' left.');
                }

                $price = (float) $product['price'];
                $total += $price * $quantity;

                $items[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total, status)
                VALUES (?, ?, 'Pending')
            ");
            $stmt->execute([$userId, $total]);
            $orderId = (int) $pdo->lastInsertId();

            foreach ($items as $item) {
                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                ]);

                $stmt = $pdo->prepare("
                    UPDATE products
                    SET remaining_stock = remaining_stock - ?,
                        stock = stock - ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $item['quantity'],
                    $item['quantity'],
                    $item['product_id'],
                ]);
            }

            $pdo->commit();

            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    // Update order status
    public static function updateStatus(int $id, string $status): bool {
        $allowed = ['Pending', 'Confirmed', 'Packed', 'Completed', 'Cancelled'];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = db()->prepare("
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ");

        return $stmt->execute([$status, $id]);
    }
}