<?php
/**
 * Checkout Page for Velvet Vogue E-Commerce Website
 * Handles order processing and payment
 */

$pageTitle = "Checkout";
require_once 'includes/header.php';
require_once 'includes/cart.php';

// Require user to be logged in
requireLogin();

$user_id = getCurrentUserId();
$cartItems = getUserCart($user_id);
$cartTotal = calculateCartTotal($user_id);

// Redirect if cart is empty
if (empty($cartItems)) {
    header("Location: cart.php?message=empty_cart");
    exit();
}

$error = '';
$success = '';

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';
    
    // Basic validation
    if (empty($shipping_address) || empty($city) || empty($state) || empty($zip_code) || empty($payment_method)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Calculate totals
        $shipping_cost = $cartTotal >= 50 ? 0 : 5.99;
        $tax = $cartTotal * 0.085;
        $total_amount = $cartTotal + $shipping_cost + $tax;
        
        $full_address = $shipping_address . ', ' . $city . ', ' . $state . ' ' . $zip_code;
        
        try {
            $pdo = getDatabaseConnection();
            $pdo->beginTransaction();
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, order_status) VALUES (?, ?, ?, 'processing')");
            $stmt->execute([$user_id, $total_amount, $full_address]);
            $order_id = $pdo->lastInsertId();
            
            // Add order items
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                
                // Update product stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            // Clear cart
            clearCart($user_id);
            
            $pdo->commit();
            
            // Redirect to success page
            header("Location: order-success.php?order_id=" . $order_id);
            exit();
            
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Checkout error: " . $e->getMessage());
            $error = 'There was an error processing your order. Please try again.';
        }
    }
}

// Calculate totals for display
$shipping_cost = $cartTotal >= 50 ? 0 : 5.99;
$tax = $cartTotal * 0.085;
$total_amount = $cartTotal + $shipping_cost + $tax;
?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold text-primary-custom">
                <i class="bi bi-credit-card"></i> Checkout
            </h1>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <form method="POST" class="needs-validation" novalidate>
                <!-- Shipping Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary-custom text-white">
                        <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Street Address *</label>
                            <input type="text" class="form-control" name="shipping_address" id="shipping_address" 
                                   value="<?php echo htmlspecialchars($_POST['shipping_address'] ?? ''); ?>" 
                                   placeholder="123 Main Street" required>
                            <div class="invalid-feedback">Please enter your street address.</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" name="city" id="city" 
                                       value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" 
                                       placeholder="New York" required>
                                <div class="invalid-feedback">Please enter your city.</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control" name="state" id="state" 
                                       value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>" 
                                       placeholder="NY" required>
                                <div class="invalid-feedback">Please enter your state.</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="zip_code" class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control" name="zip_code" id="zip_code" 
                                       value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>" 
                                       placeholder="10001" required>
                                <div class="invalid-feedback">Please enter your ZIP code.</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary-custom text-white">
                        <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="credit_card" value="credit_card" required>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="bi bi-credit-card"></i> Credit Card
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="paypal" value="paypal" required>
                                    <label class="form-check-label" for="paypal">
                                        <i class="bi bi-paypal"></i> PayPal
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <strong>Demo Mode:</strong> This is a demonstration checkout. No actual payment will be processed.
                        </div>
                    </div>
                </div>
                
                <!-- Place Order Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-check-circle"></i> Place Order ($<?php echo number_format($total_amount, 2); ?>)
                    </button>
                    <a href="cart.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Cart
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    <div class="mb-3">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                    <small class="text-muted">
                                        Qty: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <strong>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <hr>
                    
                    <!-- Totals -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($cartTotal, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="<?php echo $shipping_cost == 0 ? 'text-success' : ''; ?>">
                            <?php echo $shipping_cost == 0 ? 'FREE' : '$' . number_format($shipping_cost, 2); ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (8.5%):</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary-custom h5">
                            $<?php echo number_format($total_amount, 2); ?>
                        </strong>
                    </div>
                    
                    <!-- Security Info -->
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i> Secure SSL Encryption<br>
                            <i class="bi bi-lock"></i> Your information is protected
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

<?php require_once 'includes/footer.php'; ?>
