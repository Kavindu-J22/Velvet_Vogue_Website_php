<?php
/**
 * AJAX endpoint for removing items from cart
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

// Validate input
if (!$cart_id || !is_numeric($cart_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart ID.']);
    exit();
}

// Remove from cart
$result = removeFromCart($cart_id);
echo json_encode($result);
?>
