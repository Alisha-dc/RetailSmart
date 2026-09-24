<?php

//Product Model , handles product database

class Product {

    // Get products
    public static function getProducts(
        string $search = '',
        string $category = '',
        int $page = 1,
        int $perPage = 6
    ): array {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $where = [];
        $params = [];

        // Search by product name or description
        if ($search !== '') {
            $where[] = "(name LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        // Filter by category
        if ($category !== '') {
            $where[] = "category = ?";
            $params[] = $category;
        }

        // Build where clause
        $whereSQL = '';
        if (!empty($where)) {
            $whereSQL = ' WHERE ' . implode(' AND ', $where);
        }

        // Get total number of matching products
        $countSQL = "SELECT COUNT(*) FROM products $whereSQL";
        $countStmt = db()->prepare($countSQL);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        // Get actual products
        $sql = "
            SELECT * FROM products
            $whereSQL
            ORDER BY id DESC
            LIMIT $perPage
            OFFSET $offset
        ";

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'products' => $products,
            'total' => $total,
            'pages' => max(1, (int) ceil($total / $perPage)),
            'page' => $page,
        ];
    }

    // Find one product
    public static function find(int $id): ?array {
        $stmt = db()->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }

    // Create product
    public static function create(
        string $name,
        string $description,
        string $category,
        float $price,
        int $originalStock,
        int $remainingStock = 0,
        string $imageUrl = ''
    ): bool {
        $remainingStock = $remainingStock > 0 ? $remainingStock : $originalStock;
        $imageUrl = $imageUrl !== '' ? $imageUrl : '/RetailSmart/public/assets/images/product-placeholder.svg';

        $sql = "
            INSERT INTO products (name, description, category, price, original_stock, remaining_stock, stock, image_url)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = db()->prepare($sql);
        return $stmt->execute([
            $name,
            $description,
            $category,
            $price,
            $originalStock,
            $remainingStock,
            $remainingStock,
            $imageUrl,
        ]);
    }

    // Update product
    public static function update(
        int $id,
        string $name,
        string $description,
        string $category,
        float $price,
        int $originalStock,
        int $remainingStock = 0,
        string $imageUrl = ''
    ): bool {
        $remainingStock = $remainingStock > 0 ? $remainingStock : $originalStock;
        $imageUrl = $imageUrl !== '' ? $imageUrl : '/RetailSmart/public/assets/images/product-placeholder.svg';

        $sql = "
            UPDATE products
            SET name = ?,
                description = ?,
                category = ?,
                price = ?,
                original_stock = ?,
                remaining_stock = ?,
                stock = ?,
                image_url = ?
            WHERE id = ?
        ";

        $stmt = db()->prepare($sql);
        return $stmt->execute([
            $name,
            $description,
            $category,
            $price,
            $originalStock,
            $remainingStock,
            $remainingStock,
            $imageUrl,
            $id,
        ]);
    }

    // Delete product
    public static function delete(int $id): bool {
        $stmt = db()->prepare("
            DELETE FROM products
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    // Get product categories
    public static function categories(): array {
        $stmt = db()->query("
            SELECT DISTINCT category
            FROM products
            ORDER BY category
        ");

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
