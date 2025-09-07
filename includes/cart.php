<?php
/**
 * Shopping Cart Management System for Velvet Vogue E-Commerce Website
 * Handles cart operations: add, update, remove, and calculate totals
 */

require_once 'config.php';

/**
 * Add item to cart
 * @param int $user_id User ID
 * @param int $product_id Product ID
 * @param int $quantity Quantity to add
 * @return array Result array with success status and message
 */
function addToCart($user_id, $product_id, $quantity = 1) {
    try {
        $pdo = getDatabaseConnection();
        
        // Validate input
        if (!$user_id || !$product_id || $quantity <= 0) {
            return ['success' => false, 'message' => 'Invalid input parameters.'];
        }
        
        // Check if product exists and has sufficient stock
        $stmt = $pdo->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }
        
        if ($product['stock_quantity'] < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock available.'];
        }
        
        // Check if item already exists in cart
        $stmt = $pdo->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existingItem = $stmt->fetch();
        
        if ($existingItem) {
            // Update existing cart item
            $newQuantity = $existingItem['quantity'] + $quantity;
            
            if ($newQuantity > $product['stock_quantity']) {
                return ['success' => false, 'message' => 'Cannot add more items. Stock limit exceeded.'];
            }
            
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
            $stmt->execute([$newQuantity, $existingItem['cart_id']]);
            
            return ['success' => true, 'message' => 'Cart updated successfully!'];
        } else {
            // Add new cart item
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
            
            return ['success' => true, 'message' => 'Item added to cart successfully!'];
        }
        
    } catch (PDOException $e) {
        error_log("Error adding to cart: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to add item to cart. Please try again.'];
    }
}

/**
 * Get user's cart items with product details
 * @param int $user_id User ID
 * @return array Array of cart items with product information
 */
function getUserCart($user_id) {
    try {
        $pdo = getDatabaseConnection();
        
        $stmt = $pdo->prepare("SELECT c.cart_id, c.quantity, c.added_at,
                              p.product_id, p.product_name, p.price, p.image_path, p.stock_quantity,
                              cat.category_name
                              FROM cart c
                              JOIN products p ON c.product_id = p.product_id
                              JOIN categories cat ON p.category_id = cat.category_id
                              WHERE c.user_id = ?
                              ORDER BY c.added_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error fetching user cart: " . $e->getMessage());
        return [];
    }
}

/**
 * Update cart item quantity
 * @param int $cart_id Cart item ID
 * @param int $quantity New quantity
 * @return array Result array with success status and message
 */
function updateCartQuantity($cart_id, $quantity) {
    try {
        $pdo = getDatabaseConnection();
        
        if ($quantity <= 0) {
            return removeFromCart($cart_id);
        }
        
        // Get cart item and check stock
        $stmt = $pdo->prepare("SELECT c.product_id, p.stock_quantity 
                              FROM cart c 
                              JOIN products p ON c.product_id = p.product_id 
                              WHERE c.cart_id = ?");
        $stmt->execute([$cart_id]);
        $item = $stmt->fetch();
        
        if (!$item) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }
        
        if ($quantity > $item['stock_quantity']) {
            return ['success' => false, 'message' => 'Quantity exceeds available stock.'];
        }
        
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $stmt->execute([$quantity, $cart_id]);
        
        return ['success' => true, 'message' => 'Cart updated successfully!'];
        
    } catch (PDOException $e) {
        error_log("Error updating cart quantity: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to update cart. Please try again.'];
    }
}

/**
 * Remove item from cart
 * @param int $cart_id Cart item ID
 * @return array Result array with success status and message
 */
function removeFromCart($cart_id) {
    try {
        $pdo = getDatabaseConnection();
        
        $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ?");
        $stmt->execute([$cart_id]);
        
        return ['success' => true, 'message' => 'Item removed from cart successfully!'];
        
    } catch (PDOException $e) {
        error_log("Error removing from cart: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to remove item from cart. Please try again.'];
    }
}

/**
 * Calculate cart total for a user
 * @param int $user_id User ID
 * @return float Total cart amount
 */
function calculateCartTotal($user_id) {
    try {
        $pdo = getDatabaseConnection();
        
        $stmt = $pdo->prepare("SELECT SUM(c.quantity * p.price) as total
                              FROM cart c
                              JOIN products p ON c.product_id = p.product_id
                              WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        
        return $result['total'] ? (float)$result['total'] : 0.0;
        
    } catch (PDOException $e) {
        error_log("Error calculating cart total: " . $e->getMessage());
        return 0.0;
    }
}

/**
 * Get cart item count for a user
 * @param int $user_id User ID
 * @return int Number of items in cart
 */
function getCartItemCount($user_id) {
    try {
        $pdo = getDatabaseConnection();
        
        $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        
        return $result['count'] ? (int)$result['count'] : 0;
        
    } catch (PDOException $e) {
        error_log("Error getting cart item count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Clear user's cart
 * @param int $user_id User ID
 * @return array Result array with success status and message
 */
function clearCart($user_id) {
    try {
        $pdo = getDatabaseConnection();
        
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        return ['success' => true, 'message' => 'Cart cleared successfully!'];
        
    } catch (PDOException $e) {
        error_log("Error clearing cart: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to clear cart. Please try again.'];
    }
}
?>
