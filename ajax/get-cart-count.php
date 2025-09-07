<?php
/**
 * AJAX endpoint for getting cart item count
 */

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/cart.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['count' => 0]);
    exit();
}

$user_id = getCurrentUserId();
$count = getCartItemCount($user_id);

echo json_encode(['count' => $count]);
?>
