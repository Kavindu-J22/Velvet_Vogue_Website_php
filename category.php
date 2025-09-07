<?php
/**
 * Category Page for Velvet Vogue E-Commerce Website
 * Displays products filtered by specific category
 */

require_once 'includes/products.php';

// Get category ID from URL
$category_id = $_GET['id'] ?? '';

if (!$category_id || !is_numeric($category_id)) {
    header("Location: products.php");
    exit();
}

// Get category information
$categories = getAllCategories();
$current_category = null;

foreach ($categories as $category) {
    if ($category['category_id'] == $category_id) {
        $current_category = $category;
        break;
    }
}

if (!$current_category) {
    header("Location: products.php");
    exit();
}

$pageTitle = $current_category['category_name'];
require_once 'includes/header.php';

// Get products for this category
$products = getProductsByCategory($category_id);

// Apply sorting if specified
$sort_by = $_GET['sort_by'] ?? 'newest';
switch ($sort_by) {
    case 'price_low':
        usort($products, function($a, $b) { return $a['price'] <=> $b['price']; });
        break;
    case 'price_high':
        usort($products, function($a, $b) { return $b['price'] <=> $a['price']; });
        break;
    case 'name':
        usort($products, function($a, $b) { return strcasecmp($a['product_name'], $b['product_name']); });
        break;
    case 'newest':
    default:
        usort($products, function($a, $b) { return strtotime($b['created_at']) <=> strtotime($a['created_at']); });
        break;
}
?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($current_category['category_name']); ?></li>
                </ol>
            </nav>
            
            <div class="d-flex align-items-center mb-3">
                <div class="category-icon me-3">
                    <?php
                    $iconClass = 'bi-bag';
                    switch (strtolower($current_category['category_name'])) {
                        case 'men':
                            $iconClass = 'bi-person';
                            break;
                        case 'women':
                            $iconClass = 'bi-person-dress';
                            break;
                        case 'formal wear':
                            $iconClass = 'bi-suit-spade';
                            break;
                        case 'casual wear':
                            $iconClass = 'bi-shirt';
                            break;
                        case 'accessories':
                            $iconClass = 'bi-handbag';
                            break;
                    }
                    ?>
                    <i class="<?php echo $iconClass; ?> display-4 text-primary-custom"></i>
                </div>
                <div>
                    <h1 class="display-5 fw-bold text-primary-custom mb-0">
                        <?php echo htmlspecialchars($current_category['category_name']); ?>
                    </h1>
                    <p class="lead text-muted mb-0">
                        Explore our <?php echo strtolower($current_category['category_name']); ?> collection
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($categories as $category): ?>
                    <a href="category.php?id=<?php echo $category['category_id']; ?>" 
                       class="btn <?php echo $category['category_id'] == $category_id ? 'btn-primary-custom' : 'btn-outline-primary-custom'; ?> btn-sm">
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </a>
                <?php endforeach; ?>
                <a href="products.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-grid"></i> All Products
                </a>
            </div>
        </div>
    </div>
    
    <!-- Sort and View Options -->
    <div class="row mb-4">
        <div class="col-md-6">
            <span class="text-muted">
                Showing <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?>
                in <?php echo htmlspecialchars($current_category['category_name']); ?>
            </span>
        </div>
        <div class="col-md-6 text-md-end">
            <form method="GET" class="d-inline-flex align-items-center gap-2">
                <input type="hidden" name="id" value="<?php echo $category_id; ?>">
                <label for="sort_by" class="form-label mb-0 text-muted small">Sort by:</label>
                <select class="form-select form-select-sm" name="sort_by" id="sort_by" onchange="this.form.submit()">
                    <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>
                        Newest First
                    </option>
                    <option value="price_low" <?php echo $sort_by === 'price_low' ? 'selected' : ''; ?>>
                        Price: Low to High
                    </option>
                    <option value="price_high" <?php echo $sort_by === 'price_high' ? 'selected' : ''; ?>>
                        Price: High to Low
                    </option>
                    <option value="name" <?php echo $sort_by === 'name' ? 'selected' : ''; ?>>
                        Name: A to Z
                    </option>
                </select>
            </form>
        </div>
    </div>
    
    <?php if (!empty($products)): ?>
        <!-- Products Grid -->
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
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
                            <h5 class="product-title card-title">
                                <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" 
                                   class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['product_name']); ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                            </p>
                            
                            <div class="product-details mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-palette"></i> <?php echo htmlspecialchars($product['color']); ?> |
                                    <i class="bi bi-rulers"></i> <?php echo htmlspecialchars($product['size']); ?>
                                </small>
                            </div>
                            
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
    <?php else: ?>
        <!-- No Products Found -->
        <div class="text-center py-5">
            <i class="bi bi-box display-1 text-muted"></i>
            <h3 class="mt-3 text-muted">No Products in This Category</h3>
            <p class="text-muted">Check back soon for new arrivals in <?php echo htmlspecialchars($current_category['category_name']); ?>!</p>
            <a href="products.php" class="btn btn-primary-custom">
                <i class="bi bi-grid"></i> Browse All Products
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
