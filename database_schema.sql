-- Velvet Vogue E-Commerce Database Schema
-- Created for XAMPP/MySQL environment

-- Create the database
CREATE DATABASE IF NOT EXISTS velvet_vogue_db;
USE velvet_vogue_db;

-- Users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

-- Products table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    size ENUM('XS', 'S', 'M', 'L', 'XL', 'XXL') DEFAULT 'M',
    color VARCHAR(50),
    image_path VARCHAR(255),
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- Shopping cart table
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Orders table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status ENUM('processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'processing',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Insert sample categories
INSERT INTO categories (category_name) VALUES 
('Men'),
('Women'),
('Formal Wear'),
('Casual Wear'),
('Accessories');

-- Insert sample admin user (password: admin123)
INSERT INTO users (username, email, password, user_type) VALUES 
('admin', 'admin@velvetvogue.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample products
INSERT INTO products (category_id, product_name, description, price, size, color, image_path, stock_quantity) VALUES 
(1, 'Classic Men\'s Suit', 'Elegant black suit perfect for formal occasions', 299.99, 'L', 'Black', 'mens_suit_black.jpg', 15),
(2, 'Women\'s Evening Dress', 'Beautiful red evening dress for special events', 199.99, 'M', 'Red', 'womens_dress_red.jpg', 20),
(3, 'Business Blazer', 'Professional navy blazer for office wear', 149.99, 'L', 'Navy', 'blazer_navy.jpg', 25),
(4, 'Casual Jeans', 'Comfortable blue denim jeans for everyday wear', 79.99, 'M', 'Blue', 'jeans_blue.jpg', 30),
(5, 'Leather Handbag', 'Premium leather handbag with multiple compartments', 129.99, 'M', 'Brown', 'handbag_brown.jpg', 12);
