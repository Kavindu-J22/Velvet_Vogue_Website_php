<?php
/**
 * Search Results Page for Velvet Vogue E-Commerce Website
 * Displays products matching search query
 */

require_once 'includes/products.php';

$search_query = trim($_GET['q'] ?? '');
$pageTitle = $search_query ? "Search Results for \"$search_query\"" : "Search";

require_once 'includes/header.php';

$products = [];
if ($search_query) {
    $products = searchProducts($search_query);
}
?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Search Results</li>
                </ol>
            </nav>
            
            <?php if ($search_query): ?>
                <h1 class="display-6 fw-bold text-primary-custom">
                    <i class="bi bi-search"></i> Search Results
                </h1>
                <p class="lead text-muted">
                    Results for "<strong><?php echo htmlspecialchars($search_query); ?></strong>"
                    (<?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?> found)
                </p>
            <?php else: ?>
                <h1 class="display-6 fw-bold text-primary-custom">
                    <i class="bi bi-search"></i> Search Products
                </h1>
                <p class="lead text-muted">Find your perfect style</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="search.php">
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control" 
                                   name="q" 
                                   placeholder="Search for products, categories, colors..." 
                                   value="<?php echo htmlspecialchars($search_query); ?>"
                                   required>
                            <button class="btn btn-primary-custom" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                        <div class="form-text mt-2">
                            <small class="text-muted">
                                <i class="bi bi-lightbulb"></i>
                                Try searching for: "dress", "men's suit", "casual", "formal", "red", etc.
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($search_query): ?>
        <?php if (!empty($products)): ?>
            <!-- Search Results -->
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
                                <div class="product-category text-muted small mb-1">
                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                </div>
                                
                                <h5 class="product-title card-title">
                                    <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php 
                                        // Highlight search terms in product name
                                        $highlighted_name = str_ireplace(
                                            $search_query, 
                                            '<mark>' . htmlspecialchars($search_query) . '</mark>', 
                                            htmlspecialchars($product['product_name'])
                                        );
                                        echo $highlighted_name;
                                        ?>
                                    </a>
                                </h5>
                                
                                <p class="card-text text-muted small flex-grow-1">
                                    <?php 
                                    // Highlight search terms in description
                                    $description = substr($product['description'], 0, 100) . '...';
                                    $highlighted_description = str_ireplace(
                                        $search_query, 
                                        '<mark>' . htmlspecialchars($search_query) . '</mark>', 
                                        htmlspecialchars($description)
                                    );
                                    echo $highlighted_description;
                                    ?>
                                </p>
                                
                                <div class="product-details mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-palette"></i> 
                                        <?php 
                                        $highlighted_color = str_ireplace(
                                            $search_query, 
                                            '<mark>' . htmlspecialchars($search_query) . '</mark>', 
                                            htmlspecialchars($product['color'])
                                        );
                                        echo $highlighted_color;
                                        ?> |
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
            <!-- No Results Found -->
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No products found</h3>
                <p class="text-muted mb-4">
                    We couldn't find any products matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>".
                </p>
                
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Search suggestions:</h6>
                                <ul class="list-unstyled text-start">
                                    <li><i class="bi bi-arrow-right text-primary-custom"></i> Check your spelling</li>
                                    <li><i class="bi bi-arrow-right text-primary-custom"></i> Try more general terms</li>
                                    <li><i class="bi bi-arrow-right text-primary-custom"></i> Try different keywords</li>
                                    <li><i class="bi bi-arrow-right text-primary-custom"></i> Browse our categories instead</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="products.php" class="btn btn-primary-custom me-2">
                        <i class="bi bi-grid"></i> Browse All Products
                    </a>
                    <a href="index.php" class="btn btn-outline-primary-custom">
                        <i class="bi bi-house"></i> Back to Home
                    </a>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- Popular Searches / Categories -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-4">Popular Categories</h4>
                <div class="row g-3">
                    <?php
                    $categories = getAllCategories();
                    foreach ($categories as $category): ?>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="category.php?id=<?php echo $category['category_id']; ?>" 
                               class="btn btn-outline-primary-custom w-100">
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">Popular Searches</h4>
                <div class="d-flex flex-wrap gap-2">
                    <?php
                    $popular_searches = ['dress', 'suit', 'casual', 'formal', 'shirt', 'pants', 'accessories', 'black', 'blue', 'red'];
                    foreach ($popular_searches as $term): ?>
                        <a href="search.php?q=<?php echo urlencode($term); ?>" 
                           class="btn btn-sm btn-outline-secondary">
                            <?php echo htmlspecialchars($term); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
mark {
    background-color: #fff3cd;
    padding: 0.1em 0.2em;
    border-radius: 0.2em;
}
</style>

<?php require_once 'includes/footer.php'; ?>
