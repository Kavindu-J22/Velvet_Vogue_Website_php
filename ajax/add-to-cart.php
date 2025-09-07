<?php
/**
 * AJAX endpoint for adding items to cart
 */

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/cart.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to cart.']);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data.']);
    exit();
}

$product_id = $input['product_id'] ?? '';
$quantity = $input['quantity'] ?? 1;
$user_id = getCurrentUserId();

// Validate input
if (!$product_id || !is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity.']);
    exit();
}

// Add to cart
$result = addToCart($user_id, $product_id, $quantity);
echo json_encode($result);
?>
