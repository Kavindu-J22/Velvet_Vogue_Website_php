<?php
/**
 * Order Success Page for Velvet Vogue E-Commerce Website
 * Displays order confirmation after successful checkout
 */

$pageTitle = "Order Confirmation";
require_once 'includes/header.php';

// Require user to be logged in
requireLogin();

$order_id = $_GET['order_id'] ?? '';

if (!$order_id || !is_numeric($order_id)) {
    header("Location: index.php");
    exit();
}

// Get order details
try {
    $pdo = getDatabaseConnection();
    
    // Get order information
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$order_id, getCurrentUserId()]);
    $order = $stmt->fetch();
    
    if (!$order) {
        header("Location: index.php");
        exit();
    }
    
    // Get order items
    $stmt = $pdo->prepare("SELECT oi.*, p.product_name, p.image_path 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.product_id 
                          WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Error fetching order details: " . $e->getMessage());
    header("Location: index.php");
    exit();
}
?>

<div class="container py-4">
    <!-- Success Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="success-icon mb-4">
                <i class="bi bi-check-circle-fill display-1 text-success"></i>
            </div>
            <h1 class="display-4 fw-bold text-success mb-3">Order Confirmed!</h1>
            <p class="lead text-muted">
                Thank you for your purchase. Your order has been successfully placed.
            </p>
            <div class="alert alert-success d-inline-block" role="alert">
                <strong>Order #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></strong>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary-custom text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Order Information</h6>
                            <p class="mb-1"><strong>Order Number:</strong> #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></p>
                            <p class="mb-1"><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-info"><?php echo ucfirst($order['order_status']); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Shipping Address</h6>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <h6 class="fw-bold mb-3">Items Ordered</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($item['image_path'] && file_exists('uploads/' . $item['image_path'])): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                         class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price_at_purchase'], 2); ?></td>
                                        <td><strong>$<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- What's Next -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> What's Next?</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-envelope display-4 text-primary-custom"></i>
                            <h6 class="mt-2">Confirmation Email</h6>
                            <p class="small text-muted">You'll receive an email confirmation shortly with your order details.</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-box-seam display-4 text-primary-custom"></i>
                            <h6 class="mt-2">Processing</h6>
                            <p class="small text-muted">We'll start processing your order within 1-2 business days.</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-truck display-4 text-primary-custom"></i>
                            <h6 class="mt-2">Shipping</h6>
                            <p class="small text-muted">Your order will be shipped and you'll receive tracking information.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <?php
                    $subtotal = 0;
                    foreach ($order_items as $item) {
                        $subtotal += $item['price_at_purchase'] * $item['quantity'];
                    }
                    $shipping = $subtotal >= 50 ? 0 : 5.99;
                    $tax = $subtotal * 0.085;
                    ?>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="<?php echo $shipping == 0 ? 'text-success' : ''; ?>">
                            <?php echo $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2); ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total Paid:</strong>
                        <strong class="text-primary-custom h5">
                            $<?php echo number_format($order['total_amount'], 2); ?>
                        </strong>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary-custom" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Receipt
                        </button>
                        <a href="index.php" class="btn btn-primary-custom">
                            <i class="bi bi-house"></i> Continue Shopping
                        </a>
                    </div>
                    
                    <!-- Customer Service -->
                    <div class="mt-4 text-center">
                        <h6 class="fw-bold">Need Help?</h6>
                        <p class="small text-muted mb-2">
                            Contact our customer service team if you have any questions about your order.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="mailto:support@velvetvogue.com" class="text-primary-custom">
                                <i class="bi bi-envelope"></i>
                            </a>
                            <a href="tel:+1234567890" class="text-primary-custom">
                                <i class="bi bi-telephone"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recommended Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="text-center mb-4">You Might Also Like</h4>
            <div class="row g-4">
                <?php
                require_once 'includes/products.php';
                $recommendedProducts = getAllProducts(4);
                foreach ($recommendedProducts as $product): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card card h-100 shadow-sm">
                            <div class="position-relative">
                                <?php if ($product['image_path'] && file_exists('uploads/' . $product['image_path'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($product['image_path']); ?>" 
                                         class="product-image card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                <?php else: ?>
                                    <div class="product-image card-img-top d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-image display-4 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">
                                    <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($product['product_name']); ?>
                                    </a>
                                </h6>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="h6 text-primary-custom fw-bold mb-0">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                    <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" 
                                       class="btn btn-outline-primary-custom btn-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .footer, .btn, .recommended-products {
        display: none !important;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
