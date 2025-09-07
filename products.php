<?php
/**
 * Products Listing Page for Velvet Vogue E-Commerce Website
 * Displays all products with filtering options
 */

$pageTitle = "All Products";
require_once 'includes/header.php';
require_once 'includes/products.php';

// Get filter parameters
$category_filter = $_GET['category'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'newest';

// Get products based on filters
if ($category_filter) {
    $products = getProductsByCategory($category_filter);
} else {
    $products = getAllProducts();
}

// Apply additional filters
if ($price_min || $price_max || $sort_by !== 'newest') {
    $filteredProducts = [];
    
    foreach ($products as $product) {
        $include = true;
        
        // Price filter
        if ($price_min && $product['price'] < $price_min) {
            $include = false;
        }
        if ($price_max && $product['price'] > $price_max) {
            $include = false;
        }
        
        if ($include) {
            $filteredProducts[] = $product;
        }
    }
    
    $products = $filteredProducts;
    
    // Sort products
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
}

$categories = getAllCategories();
?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold text-primary-custom">All Products</h1>
            <p class="lead text-muted">Discover our complete collection of premium fashion items</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary-custom text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" id="product-filter-form">
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Category</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category" value="" id="category_all"
                                       <?php echo empty($category_filter) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="category_all">
                                    All Categories
                                </label>
                            </div>
                            <?php foreach ($categories as $category): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" 
                                           value="<?php echo $category['category_id']; ?>" 
                                           id="category_<?php echo $category['category_id']; ?>"
                                           <?php echo $category_filter == $category['category_id'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="category_<?php echo $category['category_id']; ?>">
                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Price Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Price Range</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="price_min" placeholder="Min" 
                                           value="<?php echo htmlspecialchars($price_min); ?>"
                                           min="0" step="0.01">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="price_max" placeholder="Max" 
                                           value="<?php echo htmlspecialchars($price_max); ?>"
                                           min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Sort By</h6>
                            <select class="form-select form-select-sm" name="sort_by">
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
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-sm">
                                <i class="bi bi-search"></i> Apply Filters
                            </button>
                            <a href="products.php" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise"></i> Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Results Info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="text-muted">
                        Showing <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?>
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="view" id="grid-view" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="grid-view">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </label>
                        
                        <input type="radio" class="btn-check" name="view" id="list-view">
                        <label class="btn btn-outline-secondary btn-sm" for="list-view">
                            <i class="bi bi-list"></i>
                        </label>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($products)): ?>
                <!-- Products Grid -->
                <div class="row g-4" id="products-container">
                    <?php foreach ($products as $product): ?>
                        <div class="col-lg-4 col-md-6 product-item">
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
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h3 class="mt-3 text-muted">No Products Found</h3>
                    <p class="text-muted">Try adjusting your filters or search criteria.</p>
                    <a href="products.php" class="btn btn-primary-custom">
                        <i class="bi bi-arrow-clockwise"></i> View All Products
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// View toggle functionality
document.getElementById('list-view').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('products-container').className = 'row g-3';
        document.querySelectorAll('.product-item').forEach(item => {
            item.className = 'col-12 product-item';
        });
    }
});

document.getElementById('grid-view').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('products-container').className = 'row g-4';
        document.querySelectorAll('.product-item').forEach(item => {
            item.className = 'col-lg-4 col-md-6 product-item';
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
