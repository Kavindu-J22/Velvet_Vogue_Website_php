<?php
/**
 * Product Detail Page for Velvet Vogue E-Commerce Website
 * Displays detailed information for a single product
 */

require_once 'includes/products.php';

// Get product ID from URL
$product_id = $_GET['id'] ?? '';

if (!$product_id || !is_numeric($product_id)) {
    header("Location: products.php");
    exit();
}

// Get product details
$product = getProductDetails($product_id);

if (!$product) {
    header("Location: products.php");
    exit();
}

$pageTitle = $product['product_name'];
require_once 'includes/header.php';

// Get related products from the same category
$relatedProducts = getProductsByCategory($product['category_id']);
$relatedProducts = array_filter($relatedProducts, function($p) use ($product_id) {
    return $p['product_id'] != $product_id;
});
$relatedProducts = array_slice($relatedProducts, 0, 4);
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="products.php">Products</a></li>
            <li class="breadcrumb-item">
                <a href="category.php?id=<?php echo $product['category_id']; ?>">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </a>
            </li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['product_name']); ?></li>
        </ol>
    </nav>
    
    <!-- Product Details -->
    <div class="row mb-5">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="product-image-container">
                <?php if ($product['image_path'] && file_exists('uploads/' . $product['image_path'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($product['image_path']); ?>" 
                         class="img-fluid rounded shadow-lg" 
                         alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                         style="width: 100%; height: 500px; object-fit: cover;">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-lg" 
                         style="width: 100%; height: 500px;">
                        <div class="text-center">
                            <i class="bi bi-image display-1 text-muted"></i>
                            <p class="text-muted mt-2">No image available</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Information -->
        <div class="col-lg-6">
            <div class="product-info">
                <!-- Category Badge -->
                <div class="mb-3">
                    <span class="badge bg-primary-custom fs-6">
                        <?php echo htmlspecialchars($product['category_name']); ?>
                    </span>
                </div>
                
                <!-- Product Name -->
                <h1 class="display-5 fw-bold text-primary-custom mb-3">
                    <?php echo htmlspecialchars($product['product_name']); ?>
                </h1>
                
                <!-- Price -->
                <div class="price-section mb-4">
                    <span class="display-6 fw-bold text-primary-custom">
                        $<?php echo number_format($product['price'], 2); ?>
                    </span>
                </div>
                
                <!-- Product Details -->
                <div class="product-details mb-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <i class="bi bi-palette text-primary-custom"></i>
                                <strong>Color:</strong>
                                <span class="ms-2"><?php echo htmlspecialchars($product['color']); ?></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <i class="bi bi-rulers text-primary-custom"></i>
                                <strong>Size:</strong>
                                <span class="ms-2"><?php echo htmlspecialchars($product['size']); ?></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <i class="bi bi-box text-primary-custom"></i>
                                <strong>Stock:</strong>
                                <span class="ms-2">
                                    <?php if ($product['stock_quantity'] > 10): ?>
                                        <span class="text-success">In Stock (<?php echo $product['stock_quantity']; ?>)</span>
                                    <?php elseif ($product['stock_quantity'] > 0): ?>
                                        <span class="text-warning">Low Stock (<?php echo $product['stock_quantity']; ?>)</span>
                                    <?php else: ?>
                                        <span class="text-danger">Out of Stock</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <i class="bi bi-calendar text-primary-custom"></i>
                                <strong>Added:</strong>
                                <span class="ms-2"><?php echo date('M j, Y', strtotime($product['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="description mb-4">
                    <h5 class="fw-bold mb-3">Description</h5>
                    <p class="text-muted lh-lg">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>
                </div>
                
                <!-- Add to Cart Section -->
                <div class="add-to-cart-section">
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <?php if (isLoggedIn()): ?>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <label for="quantity" class="form-label mb-0 fw-bold">Quantity:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" id="quantity" class="quantity-input" value="1" 
                                           min="1" max="<?php echo $product['stock_quantity']; ?>">
                                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary-custom btn-lg add-to-cart-btn" 
                                        data-product-id="<?php echo $product['product_id']; ?>"
                                        onclick="addToCartWithQuantity()">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-primary-custom btn-lg">
                                    <i class="bi bi-heart"></i> Add to Wishlist
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i>
                                Please <a href="login.php" class="alert-link">log in</a> to add items to your cart.
                            </div>
                            <div class="d-grid gap-2">
                                <a href="login.php" class="btn btn-primary-custom btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Purchase
                                </a>
                                <a href="register.php" class="btn btn-outline-primary-custom btn-lg">
                                    <i class="bi bi-person-plus"></i> Create Account
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            This item is currently out of stock.
                        </div>
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="bi bi-x-circle"></i> Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
                
                <!-- Additional Info -->
                <div class="additional-info mt-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <i class="bi bi-truck display-6 text-primary-custom"></i>
                            <p class="small text-muted mt-2 mb-0">Free Shipping<br>on orders over $50</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-arrow-clockwise display-6 text-primary-custom"></i>
                            <p class="small text-muted mt-2 mb-0">Easy Returns<br>30-day policy</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-shield-check display-6 text-primary-custom"></i>
                            <p class="small text-muted mt-2 mb-0">Secure Payment<br>SSL encrypted</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="related-products">
            <h3 class="fw-bold text-primary-custom mb-4">Related Products</h3>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card card h-100 shadow-sm">
                            <div class="position-relative">
                                <?php if ($relatedProduct['image_path'] && file_exists('uploads/' . $relatedProduct['image_path'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($relatedProduct['image_path']); ?>" 
                                         class="product-image card-img-top" 
                                         alt="<?php echo htmlspecialchars($relatedProduct['product_name']); ?>">
                                <?php else: ?>
                                    <div class="product-image card-img-top d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-image display-4 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">
                                    <a href="product-detail.php?id=<?php echo $relatedProduct['product_id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($relatedProduct['product_name']); ?>
                                    </a>
                                </h6>
                                
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="h6 text-primary-custom fw-bold mb-0">
                                        $<?php echo number_format($relatedProduct['price'], 2); ?>
                                    </span>
                                    <a href="product-detail.php?id=<?php echo $relatedProduct['product_id']; ?>" 
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
    <?php endif; ?>
</div>

<script>
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function addToCartWithQuantity() {
    const productId = <?php echo $product['product_id']; ?>;
    const quantity = document.getElementById('quantity').value;
    
    // Use the existing addToCart function from main.js
    addToCart(productId, quantity);
}
</script>

<?php require_once 'includes/footer.php'; ?>
