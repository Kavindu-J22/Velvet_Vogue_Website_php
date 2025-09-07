<?php
/**
 * Admin Dashboard for Velvet Vogue E-Commerce Website
 * Protected page for admin users to manage products and view statistics
 */

$pageTitle = "Admin Dashboard";
require_once 'includes/header.php';
require_once 'includes/products.php';

// Require admin access
requireAdmin();

$message = '';
$error = '';

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_product') {
        $category_id = $_POST['category_id'] ?? '';
        $product_name = trim($_POST['product_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? '';
        $size = $_POST['size'] ?? 'M';
        $color = trim($_POST['color'] ?? '');
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        
        $image_path = '';
        
        // Handle image upload
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = handleImageUpload($_FILES['product_image']);
            if ($upload_result['success']) {
                $image_path = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (!$error) {
            $result = addProduct($category_id, $product_name, $description, $price, $size, $color, $image_path, $stock_quantity);
            if ($result['success']) {
                $message = $result['message'];
                // Clear form data
                $_POST = [];
            } else {
                $error = $result['message'];
            }
        }
    } elseif ($_POST['action'] === 'delete_product') {
        $product_id = $_POST['product_id'] ?? '';
        if ($product_id) {
            $result = deleteProduct($product_id);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    }
}

// Get all products and categories for display
$products = getAllProducts();
$categories = getAllCategories();

// Calculate some basic statistics
$total_products = count($products);
$total_categories = count($categories);
$low_stock_products = array_filter($products, function($p) { return $p['stock_quantity'] <= 5; });
$out_of_stock_products = array_filter($products, function($p) { return $p['stock_quantity'] == 0; });
?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold text-primary-custom">
                <i class="bi bi-speedometer2"></i> Admin Dashboard
            </h1>
            <p class="lead text-muted">Manage your Velvet Vogue store</p>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary-custom text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo $total_products; ?></h4>
                            <p class="mb-0">Total Products</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo $total_categories; ?></h4>
                            <p class="mb-0">Categories</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-grid display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo count($low_stock_products); ?></h4>
                            <p class="mb-0">Low Stock Items</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo count($out_of_stock_products); ?></h4>
                            <p class="mb-0">Out of Stock</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-x-circle display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Add Product Form -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary-custom text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Product</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="action" value="add_product">
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>"
                                            <?php echo (($_POST['category_id'] ?? '') == $category['category_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" name="product_name" id="product_name" 
                                   value="<?php echo htmlspecialchars($_POST['product_name'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Please enter a product name.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3"
                                      placeholder="Enter product description..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="price" class="form-label">Price ($) *</label>
                                <input type="number" class="form-control" name="price" id="price" 
                                       value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" 
                                       step="0.01" min="0" required>
                                <div class="invalid-feedback">Please enter a valid price.</div>
                            </div>
                            
                            <div class="col-6 mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                <input type="number" class="form-control" name="stock_quantity" id="stock_quantity" 
                                       value="<?php echo htmlspecialchars($_POST['stock_quantity'] ?? '0'); ?>" 
                                       min="0" required>
                                <div class="invalid-feedback">Please enter stock quantity.</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="size" class="form-label">Size</label>
                                <select class="form-select" name="size" id="size">
                                    <option value="XS" <?php echo (($_POST['size'] ?? '') === 'XS') ? 'selected' : ''; ?>>XS</option>
                                    <option value="S" <?php echo (($_POST['size'] ?? '') === 'S') ? 'selected' : ''; ?>>S</option>
                                    <option value="M" <?php echo (($_POST['size'] ?? 'M') === 'M') ? 'selected' : ''; ?>>M</option>
                                    <option value="L" <?php echo (($_POST['size'] ?? '') === 'L') ? 'selected' : ''; ?>>L</option>
                                    <option value="XL" <?php echo (($_POST['size'] ?? '') === 'XL') ? 'selected' : ''; ?>>XL</option>
                                    <option value="XXL" <?php echo (($_POST['size'] ?? '') === 'XXL') ? 'selected' : ''; ?>>XXL</option>
                                </select>
                            </div>
                            
                            <div class="col-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" id="color" 
                                       value="<?php echo htmlspecialchars($_POST['color'] ?? ''); ?>" 
                                       placeholder="e.g., Black, Red, Blue">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="product_image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="product_image" id="product_image" 
                                   accept="image/*">
                            <div class="form-text">Upload an image (JPEG, PNG, GIF, WebP). Max size: 5MB.</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-plus-circle"></i> Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products List -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list"></i> All Products</h5>
                    <span class="badge bg-primary-custom"><?php echo $total_products; ?> products</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <?php if ($product['image_path'] && file_exists('uploads/' . $product['image_path'])): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($product['image_path']); ?>" 
                                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($product['product_name']); ?></strong><br>
                                                <small class="text-muted">
                                                    <?php echo htmlspecialchars($product['color']); ?> | 
                                                    <?php echo htmlspecialchars($product['size']); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-primary-custom">
                                                    $<?php echo number_format($product['price'], 2); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php if ($product['stock_quantity'] == 0): ?>
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                <?php elseif ($product['stock_quantity'] <= 5): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        Low Stock (<?php echo $product['stock_quantity']; ?>)
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        In Stock (<?php echo $product['stock_quantity']; ?>)
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" 
                                                       class="btn btn-outline-primary" target="_blank">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <form method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                        <input type="hidden" name="action" value="delete_product">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-box display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No products found</h5>
                            <p class="text-muted">Add your first product using the form on the left.</p>
                        </div>
                    <?php endif; ?>
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
