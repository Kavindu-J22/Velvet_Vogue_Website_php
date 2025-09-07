<?php
/**
 * AJAX endpoint for updating cart item quantity
 */

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/cart.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in.']);
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

$cart_id = $input['cart_id'] ?? '';
$quantity = $input['quantity'] ?? 0;

// Validate input
if (!$cart_id || !is_numeric($cart_id) || !is_numeric($quantity) || $quantity < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart ID or quantity.']);
    exit();
}

// Update cart quantity
$result = updateCartQuantity($cart_id, $quantity);
echo json_encode($result);
?>
