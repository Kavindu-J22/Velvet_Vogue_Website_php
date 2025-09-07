<?php
/**
 * Shopping Cart Page for Velvet Vogue E-Commerce Website
 * Displays cart items and allows quantity management
 */

$pageTitle = "Shopping Cart";
require_once 'includes/header.php';
require_once 'includes/cart.php';

// Require user to be logged in
requireLogin();

$user_id = getCurrentUserId();
$cartItems = getUserCart($user_id);
$cartTotal = calculateCartTotal($user_id);

// Handle cart updates via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_quantity') {
        $cart_id = $_POST['cart_id'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;
        
        if ($cart_id && $quantity > 0) {
            $result = updateCartQuantity($cart_id, $quantity);
            if ($result['success']) {
                header("Location: cart.php?message=updated");
                exit();
            }
        }
    } elseif ($action === 'remove_item') {
        $cart_id = $_POST['cart_id'] ?? '';
        
        if ($cart_id) {
            $result = removeFromCart($cart_id);
            if ($result['success']) {
                header("Location: cart.php?message=removed");
                exit();
            }
        }
    } elseif ($action === 'clear_cart') {
        $result = clearCart($user_id);
        if ($result['success']) {
            header("Location: cart.php?message=cleared");
            exit();
        }
    }
}
?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Shopping Cart</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold text-primary-custom">
                <i class="bi bi-cart3"></i> Your Shopping Cart
            </h1>
        </div>
    </div>
    
    <?php if (!empty($cartItems)): ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary-custom text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-bag"></i> Cart Items (<?php echo count($cartItems); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item p-4 border-bottom">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-3 mb-3 mb-md-0">
                                        <?php if ($item['image_path'] && file_exists('uploads/' . $item['image_path'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                 class="cart-item-image img-fluid rounded" 
                                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                        <?php else: ?>
                                            <div class="cart-item-image bg-light rounded d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="col-md-4 col-9 mb-3 mb-md-0">
                                        <h6 class="mb-1">
                                            <a href="product-detail.php?id=<?php echo $item['product_id']; ?>" 
                                               class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($item['product_name']); ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($item['category_name']); ?>
                                        </small>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                Price: $<?php echo number_format($item['price'], 2); ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="update_quantity">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <div class="quantity-controls">
                                                <button type="button" class="quantity-btn" 
                                                        onclick="updateQuantity(<?php echo $item['cart_id']; ?>, -1)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" name="quantity" 
                                                       class="quantity-input" 
                                                       value="<?php echo $item['quantity']; ?>"
                                                       min="1" max="<?php echo $item['stock_quantity']; ?>"
                                                       data-cart-id="<?php echo $item['cart_id']; ?>"
                                                       onchange="this.form.submit()">
                                                <button type="button" class="quantity-btn" 
                                                        onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 1)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <?php if ($item['quantity'] > $item['stock_quantity']): ?>
                                            <small class="text-danger">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Only <?php echo $item['stock_quantity']; ?> in stock
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Item Total & Actions -->
                                    <div class="col-md-3 col-6 text-md-end">
                                        <div class="item-total h6 text-primary-custom fw-bold mb-2">
                                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </div>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="remove_item">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm remove-from-cart-btn"
                                                    onclick="return confirm('Remove this item from cart?')">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Cart Actions -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="products.php" class="btn btn-outline-primary-custom">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="action" value="clear_cart">
                        <button type="submit" class="btn btn-outline-danger"
                                onclick="return confirm('Clear all items from cart?')">
                            <i class="bi bi-trash"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt"></i> Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="cart-total">$<?php echo number_format($cartTotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="text-success">
                                <?php echo $cartTotal >= 50 ? 'FREE' : '$5.99'; ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8.5%):</span>
                            <span>$<?php echo number_format($cartTotal * 0.085, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary-custom h5">
                                $<?php 
                                $shipping = $cartTotal >= 50 ? 0 : 5.99;
                                $tax = $cartTotal * 0.085;
                                $total = $cartTotal + $shipping + $tax;
                                echo number_format($total, 2); 
                                ?>
                            </strong>
                        </div>
                        
                        <?php if ($cartTotal >= 50): ?>
                            <div class="alert alert-success alert-sm" role="alert">
                                <i class="bi bi-check-circle"></i>
                                <small>You qualify for free shipping!</small>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info alert-sm" role="alert">
                                <i class="bi bi-info-circle"></i>
                                <small>Add $<?php echo number_format(50 - $cartTotal, 2); ?> more for free shipping</small>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="checkout.php" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>
                            <button class="btn btn-outline-secondary">
                                <i class="bi bi-paypal"></i> PayPal Express
                            </button>
                        </div>
                        
                        <!-- Security Badges -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i> Secure SSL Encryption<br>
                                <i class="bi bi-credit-card"></i> All major cards accepted
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3 text-muted">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="products.php" class="btn btn-primary-custom btn-lg">
                <i class="bi bi-bag"></i> Start Shopping
            </a>
        </div>
        
        <!-- Suggested Products -->
        <div class="mt-5">
            <h4 class="text-center mb-4">You might like these</h4>
            <div class="row g-4">
                <?php
                $suggestedProducts = getAllProducts(4);
                foreach ($suggestedProducts as $product): ?>
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
                                    <button class="btn btn-primary-custom btn-sm add-to-cart-btn" 
                                            data-product-id="<?php echo $product['product_id']; ?>">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(cartId, change) {
    const quantityInput = document.querySelector(`input[data-cart-id="${cartId}"]`);
    let currentQuantity = parseInt(quantityInput.value);
    let newQuantity = currentQuantity + change;
    
    if (newQuantity >= 1 && newQuantity <= parseInt(quantityInput.max)) {
        quantityInput.value = newQuantity;
        quantityInput.form.submit();
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
