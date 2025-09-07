<?php

/**
 * Homepage for Velvet Vogue E-Commerce Website
 * Displays hero section, featured products, and categories
 */

$pageTitle = "Home";
require_once 'includes/header.php';
require_once 'includes/products.php';

// Get featured products (latest 8 products)
$featuredProducts = getAllProducts(8);
$categories = getAllCategories();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to Velvet Vogue</h1>
                <p class="lead mb-4">Discover the latest fashion trends and timeless elegance. From casual wear to formal attire, find your perfect style with our curated collection of premium clothing.</p>
                <div class="d-flex gap-3">
                    <a href="products.php" class="btn btn-light btn-lg">
                        <i class="bi bi-bag"></i> Shop Now
                    </a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-arrow-down"></i> Explore
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image mt-4 mt-lg-0">
                    <i class="bi bi-gem display-1 text-light opacity-75"></i>
                    <h3 class="mt-3">Premium Fashion</h3>
                    <p>Quality • Style • Elegance</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold text-primary-custom">Shop by Category</h2>
                <p class="lead text-muted">Find exactly what you're looking for</p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="text-center">
                        <a href="category.php?id=<?php echo $category['category_id']; ?>"
                            class="text-decoration-none">
                            <div class="category-card p-4 border rounded-3 shadow-sm h-100 
                                        d-flex flex-column align-items-center justify-content-center
                                        transition-all hover-shadow">
                                <div class="category-icon mb-3">
                                    <?php
                                    // Simple icon mapping based on category name with valid Bootstrap icons
                                    $iconClass = 'bi-bag';
                                    switch (strtolower($category['category_name'])) {
                                        case 'men':
                                            $iconClass = 'bi-person-fill';
                                            break;
                                        case 'women':
                                            $iconClass = 'bi-person-hearts';
                                            break;
                                        case 'formal wear':
                                            $iconClass = 'bi-briefcase-fill';
                                            break;
                                        case 'casual wear':
                                            $iconClass = 'bi-house-heart-fill';
                                            break;
                                        case 'accessories':
                                            $iconClass = 'bi-handbag-fill';
                                            break;
                                    }
                                    ?>
                                    <i class="<?php echo $iconClass; ?> display-6 text-primary-custom"></i>
                                </div>
                                <h5 class="category-name text-dark mb-0">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </h5>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured-products" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold text-primary-custom">Featured Products</h2>
                <p class="lead text-muted">Discover our latest and most popular items</p>
            </div>
        </div>

        <?php if (!empty($featuredProducts)): ?>
            <div class="row g-4">
                <?php foreach ($featuredProducts as $product): ?>
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

                                <?php if ($product['stock_quantity'] <= 5 && $product['stock_quantity'] > 0): ?>
                                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                        Low Stock
                                    </span>
                                <?php elseif ($product['stock_quantity'] == 0): ?>
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                        Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="product-card-body card-body d-flex flex-column">
                                <div class="product-category text-muted small mb-1">
                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                </div>

                                <h5 class="product-title card-title">
                                    <a href="product-detail.php?id=<?php echo $product['product_id']; ?>"
                                        class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($product['product_name']); ?>
                                    </a>
                                </h5>

                                <p class="card-text text-muted small flex-grow-1">
                                    <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="product-price h5 mb-0 text-primary-custom fw-bold">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </div>

                                    <?php if ($product['stock_quantity'] > 0): ?>
                                        <?php if (isLoggedIn()): ?>
                                            <button class="btn btn-primary-custom btn-sm add-to-cart-btn"
                                                data-product-id="<?php echo $product['product_id']; ?>">
                                                <i class="bi bi-cart-plus"></i> Add to Cart
                                            </button>
                                        <?php else: ?>
                                            <a href="login.php" class="btn btn-outline-primary-custom btn-sm">
                                                <i class="bi bi-box-arrow-in-right"></i> Login to Buy
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="bi bi-x-circle"></i> Out of Stock
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-5">
                <a href="products.php" class="btn btn-primary-custom btn-lg">
                    <i class="bi bi-grid"></i> View All Products
                </a>
            </div>
        <?php else: ?>
            <div class="text-center">
                <div class="py-5">
                    <i class="bi bi-box display-1 text-muted"></i>
                    <h3 class="mt-3 text-muted">No Products Available</h3>
                    <p class="text-muted">Check back soon for new arrivals!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-primary-custom text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3">Stay Updated with Velvet Vogue</h3>
                <p class="mb-0">Subscribe to our newsletter and be the first to know about new arrivals, exclusive offers, and fashion tips.</p>
            </div>
            <div class="col-lg-6">
                <form class="d-flex gap-2 mt-3 mt-lg-0">
                    <input type="email" class="form-control" placeholder="Enter your email address" required>
                    <button type="submit" class="btn btn-light text-primary-custom fw-bold">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>