-- Create the database
CREATE DATABASE IF NOT EXISTS retailsmart;

-- Select the database
USE retailsmart;



-- USERS TABLE


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('customer', 'admin')
        NOT NULL DEFAULT 'customer',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- PRODUCTS TABLE


CREATE TABLE IF NOT EXISTS products (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,

    description TEXT,

    category VARCHAR(100) NOT NULL,

    price DECIMAL(10,2) NOT NULL,

    original_stock INT NOT NULL DEFAULT 0,

    remaining_stock INT NOT NULL DEFAULT 0,

    stock INT NOT NULL DEFAULT 0,

    image_url VARCHAR(255) NOT NULL DEFAULT '/RetailSmart/public/assets/images/product-placeholder.svg',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE products
    ADD COLUMN IF NOT EXISTS original_stock INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS remaining_stock INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS image_url VARCHAR(255) NOT NULL DEFAULT '/RetailSmart/public/assets/images/product-placeholder.svg';

UPDATE products
SET original_stock = COALESCE(original_stock, stock),
    remaining_stock = COALESCE(remaining_stock, stock),
    stock = COALESCE(remaining_stock, stock),
    image_url = COALESCE(image_url, '/RetailSmart/public/assets/images/product-placeholder.svg')
WHERE original_stock IS NULL OR remaining_stock IS NULL OR stock IS NULL OR image_url IS NULL;



-- ORDERS TABLE


CREATE TABLE IF NOT EXISTS orders (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    total DECIMAL(10,2) NOT NULL DEFAULT 0,

    status ENUM(
        'Pending',
        'Confirmed',
        'Packed',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);



-- ORDER ITEMS TABLE


CREATE TABLE IF NOT EXISTS order_items (

    id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,

    product_id INT NOT NULL,

    quantity INT NOT NULL,

    price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);


-- AUDIT LOGS TABLE


CREATE TABLE IF NOT EXISTS audit_logs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NULL,

    action VARCHAR(100) NOT NULL,

    details TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);


-- SAMPLE PRODUCTS


INSERT INTO products
    (name, description, category, price, stock)
VALUES

(
    'Ceramic Coffee Mug',
    'Simple ceramic mug for everyday use.',
    'Home',
    12.99,
    20
),

(
    'Notebook',
    'A5 lined notebook for school and office use.',
    'Stationery',
    8.50,
    35
),

(
    'Reusable Water Bottle',
    'Reusable bottle suitable for everyday use.',
    'Accessories',
    18.99,
    25
),

(
    'Shopping Bag',
    'Reusable shopping bag.',
    'Accessories',
    5.99,
    50
);