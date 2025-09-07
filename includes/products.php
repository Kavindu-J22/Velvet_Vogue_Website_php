<?php

/**
 * Product Management System for Velvet Vogue E-Commerce Website
 * Handles product CRUD operations and image uploads
 */

require_once 'config.php';

/**
 * Handle image upload for products
 * @param array $file $_FILES array element
 * @return array Result array with success status, message, and filename
 */
function handleImageUpload($file)
{
    $uploadDir = 'uploads/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or upload error occurred.'];
    }

    // Validate file type
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'];
    }

    // Validate file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 5MB.'];
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_') . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'message' => 'Image uploaded successfully.', 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file.'];
    }
}

/**
 * Get all products with category information
 * @param int $limit Optional limit for number of products
 * @param int $offset Optional offset for pagination
 * @return array Array of products
 */
function getAllProducts($limit = null, $offset = 0)
{
    try {
        $pdo = getDatabaseConnection();

        $sql = "SELECT p.*, c.category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.category_id 
                ORDER BY p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching products: " . $e->getMessage());
        return [];
    }
}

/**
 * Get products by category
 * @param int $category_id Category ID to filter by
 * @return array Array of products in the category
 */
function getProductsByCategory($category_id)
{
    try {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare("SELECT p.*, c.category_name 
                              FROM products p 
                              JOIN categories c ON p.category_id = c.category_id 
                              WHERE p.category_id = ? 
                              ORDER BY p.created_at DESC");
        $stmt->execute([$category_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching products by category: " . $e->getMessage());
        return [];
    }
}

/**
 * Get single product details
 * @param int $product_id Product ID
 * @return array|null Product details or null if not found
 */
function getProductDetails($product_id)
{
    try {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare("SELECT p.*, c.category_name 
                              FROM products p 
                              JOIN categories c ON p.category_id = c.category_id 
                              WHERE p.product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching product details: " . $e->getMessage());
        return null;
    }
}

/**
 * Add new product (Admin only)
 * @param int $category_id Category ID
 * @param string $product_name Product name
 * @param string $description Product description
 * @param float $price Product price
 * @param string $size Product size
 * @param string $color Product color
 * @param string $image_path Image file path
 * @param int $stock_quantity Stock quantity
 * @return array Result array with success status and message
 */
function addProduct($category_id, $product_name, $description, $price, $size, $color, $image_path, $stock_quantity)
{
    try {
        $pdo = getDatabaseConnection();

        // Validate input
        if (empty($product_name) || empty($price) || $price <= 0) {
            return ['success' => false, 'message' => 'Product name and valid price are required.'];
        }

        $stmt = $pdo->prepare("INSERT INTO products (category_id, product_name, description, price, size, color, image_path, stock_quantity) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $product_name, $description, $price, $size, $color, $image_path, $stock_quantity]);

        return ['success' => true, 'message' => 'Product added successfully!', 'product_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        error_log("Error adding product: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to add product. Please try again.'];
    }
}

/**
 * Update product information
 * @param int $product_id Product ID to update
 * @param array $data Array of data to update
 * @return array Result array with success status and message
 */
function updateProduct($product_id, $data)
{
    try {
        $pdo = getDatabaseConnection();

        $allowedFields = ['category_id', 'product_name', 'description', 'price', 'size', 'color', 'image_path', 'stock_quantity'];
        $updateFields = [];
        $values = [];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updateFields[] = "$field = ?";
                $values[] = $value;
            }
        }

        if (empty($updateFields)) {
            return ['success' => false, 'message' => 'No valid fields to update.'];
        }

        $values[] = $product_id;
        $sql = "UPDATE products SET " . implode(', ', $updateFields) . " WHERE product_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        return ['success' => true, 'message' => 'Product updated successfully!'];
    } catch (PDOException $e) {
        error_log("Error updating product: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to update product. Please try again.'];
    }
}

/**
 * Delete product
 * @param int $product_id Product ID to delete
 * @return array Result array with success status and message
 */
function deleteProduct($product_id)
{
    try {
        $pdo = getDatabaseConnection();

        // Get product details to delete image file
        $product = getProductDetails($product_id);

        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);

        // Delete image file if exists
        if ($product && $product['image_path'] && file_exists("uploads/" . $product['image_path'])) {
            unlink("uploads/" . $product['image_path']);
        }

        return ['success' => true, 'message' => 'Product deleted successfully!'];
    } catch (PDOException $e) {
        error_log("Error deleting product: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to delete product. Please try again.'];
    }
}

/**
 * Get all categories
 * @return array Array of categories
 */
function getAllCategories()
{
    try {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->query("SELECT * FROM categories ORDER BY category_name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching categories: " . $e->getMessage());
        return [];
    }
}

/**
 * Search products by name or description
 * @param string $search_term Search term
 * @return array Array of matching products
 */
function searchProducts($search_term)
{
    try {
        $pdo = getDatabaseConnection();

        $search_term = "%$search_term%";
        $stmt = $pdo->prepare("SELECT p.*, c.category_name 
                              FROM products p 
                              JOIN categories c ON p.category_id = c.category_id 
                              WHERE p.product_name LIKE ? OR p.description LIKE ? 
                              ORDER BY p.created_at DESC");
        $stmt->execute([$search_term, $search_term]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error searching products: " . $e->getMessage());
        return [];
    }
}
