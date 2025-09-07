<?php
/**
 * AJAX endpoint for getting cart total
 */

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/cart.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['total' => 0]);
    exit();
}

$user_id = getCurrentUserId();
$total = calculateCartTotal($user_id);

echo json_encode(['total' => $total]);
?>
