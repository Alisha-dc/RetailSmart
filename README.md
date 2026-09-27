# RetailSmart

RetailSmart is a full-stack retail management system developed using PHP, MySQL, HTML, CSS and JavaScript.

## Technology Stack

- HTML5
- CSS3
- JavaScript
- PHP 8+
- MySQL
- PDO
- Sessions

## Main Features

### Customer

- Register
- Login
- Logout
- Browse products
- Search products
- Filter products by category
- Pagination
- Add products to cart
- Remove products from cart
- Place orders
- View order history
- View order details
- Smart Help Assistant

### Administrator

- Admin dashboard
- Product CRUD
- User management
- Role management
- Order management
- Order status updates
- Audit logging

## Security

The application uses:

- Password hashing
- PDO prepared statements
- CSRF tokens
- Session authentication
- Role-based access control
- HTML output escaping
- Server-side validation
- Database transactions

## Intelligent Feature

RetailSmart includes a rule-based Smart Help Assistant.

The assistant recognises common keywords and provides predefined answers about:

- Products
- Stock
- Cart
- Orders
- Returns
- Accounts
- Payments

The feature does not make high-risk decisions and does not process real payments.

## Installation

1. Install XAMPP.
2. Start Apache and MySQL.
3. Copy the RetailSmart folder into:

C:\xampp\htdocs\

4. Open phpMyAdmin.
5. Run the SQL script in database/schema.sql.
6. Open:

http://localhost:82/RetailSmart/public/

## Default Admin Login

Admin credentials should be created during setup or by importing the seed data from the database script. Use a strong password and keep local credentials out of the public repository.

- Email: use a secure admin email for your local environment
- Password: set a strong password during setup

## Customer Login

New customers can register from the register page and then log in normally.

## Product Images

The app supports product image URLs and includes default placeholder images in:

public/assets/images/

## Database

Database name:

retailsmart

Tables:

- users
- products
- orders
- order_items
- audit_logs