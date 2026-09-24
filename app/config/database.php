<?php
// Load database configuration
require_once __DIR__ . '/config.php';

// This function creates the database connection
function db(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $pdo;
    } catch (PDOException $e) {
        // Do not show database password/error details
        die('Database connection failed.');
    }
}
