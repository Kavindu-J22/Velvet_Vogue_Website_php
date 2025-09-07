<?php
/**
 * Installation Script for Velvet Vogue E-Commerce Website
 * Helps verify setup and configuration
 */

// Prevent running if already installed (basic check)
if (file_exists('INSTALLED.txt')) {
    die('<h1>Installation Complete</h1><p>The website is already installed. Delete INSTALLED.txt to run this script again.</p>');
}

$errors = [];
$warnings = [];
$success = [];

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    $errors[] = 'PHP 7.4 or higher is required. Current version: ' . PHP_VERSION;
} else {
    $success[] = 'PHP version: ' . PHP_VERSION . ' ✓';
}

// Check required PHP extensions
$required_extensions = ['pdo', 'pdo_mysql', 'gd', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Required PHP extension '$ext' is not loaded";
    } else {
        $success[] = "PHP extension '$ext' is loaded ✓";
    }
}

// Check database connection
try {
    require_once 'includes/config.php';
    $pdo = getDatabaseConnection();
    $success[] = 'Database connection successful ✓';
    
    // Check if tables exist
    $tables = ['users', 'categories', 'products', 'cart', 'orders', 'order_items'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $success[] = "Table '$table' exists ✓";
        } else {
            $errors[] = "Table '$table' does not exist. Please import database_schema.sql";
        }
    }
    
    // Check for admin user
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'admin'");
    $result = $stmt->fetch();
    if ($result['count'] > 0) {
        $success[] = 'Admin user exists ✓';
    } else {
        $warnings[] = 'No admin user found. You may need to create one.';
    }
    
} catch (Exception $e) {
    $errors[] = 'Database connection failed: ' . $e->getMessage();
}

// Check directory permissions
$directories = ['uploads', 'css', 'js', 'images'];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        $warnings[] = "Directory '$dir' does not exist";
    } elseif (!is_writable($dir)) {
        $warnings[] = "Directory '$dir' is not writable";
    } else {
        $success[] = "Directory '$dir' is writable ✓";
    }
}

// Check critical files
$critical_files = [
    'includes/config.php',
    'includes/auth.php',
    'includes/products.php',
    'includes/cart.php',
    'index.php',
    'database_schema.sql'
];

foreach ($critical_files as $file) {
    if (!file_exists($file)) {
        $errors[] = "Critical file '$file' is missing";
    } else {
        $success[] = "File '$file' exists ✓";
    }
}

// If installation successful, create marker file
if (empty($errors)) {
    file_put_contents('INSTALLED.txt', 'Velvet Vogue installed on ' . date('Y-m-d H:i:s'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B4B8C;
        }
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary-custom text-white text-center">
                        <h1 class="mb-0">
                            <i class="bi bi-gem"></i> Velvet Vogue Installation
                        </h1>
                        <p class="mb-0">E-Commerce Website Setup Verification</p>
                    </div>
                    <div class="card-body">
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <h5><i class="bi bi-exclamation-triangle"></i> Critical Errors</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr>
                                <p class="mb-0"><strong>Please fix these errors before proceeding.</strong></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($warnings)): ?>
                            <div class="alert alert-warning">
                                <h5><i class="bi bi-exclamation-circle"></i> Warnings</h5>
                                <ul class="mb-0">
                                    <?php foreach ($warnings as $warning): ?>
                                        <li><?php echo htmlspecialchars($warning); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <h5><i class="bi bi-check-circle"></i> System Check Results</h5>
                                <ul class="mb-0">
                                    <?php foreach ($success as $item): ?>
                                        <li><?php echo htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (empty($errors)): ?>
                            <div class="alert alert-success">
                                <h4><i class="bi bi-check-circle-fill"></i> Installation Complete!</h4>
                                <p>Your Velvet Vogue e-commerce website is ready to use.</p>
                                
                                <h6>Default Login Credentials:</h6>
                                <ul>
                                    <li><strong>Admin:</strong> username: <code>admin</code>, password: <code>admin123</code></li>
                                    <li><strong>Customer:</strong> Register a new account on the website</li>
                                </ul>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                    <a href="index.php" class="btn btn-primary-custom btn-lg">
                                        <i class="bi bi-house"></i> Visit Website
                                    </a>
                                    <a href="admin-dashboard.php" class="btn btn-outline-primary">
                                        <i class="bi bi-speedometer2"></i> Admin Dashboard
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <h5><i class="bi bi-info-circle"></i> Next Steps</h5>
                                <ol>
                                    <li>Fix any critical errors listed above</li>
                                    <li>Import <code>database_schema.sql</code> into phpMyAdmin if not done</li>
                                    <li>Ensure all directories have proper permissions</li>
                                    <li>Refresh this page to re-run the installation check</li>
                                </ol>
                            </div>
                        <?php endif; ?>
                        
                        <!-- System Information -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-info-square"></i> System Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
                                        <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
                                        <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Current Directory:</strong> <?php echo __DIR__; ?></p>
                                        <p><strong>Installation Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                                        <p><strong>Memory Limit:</strong> <?php echo ini_get('memory_limit'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="text-center mt-4">
                            <h6>Quick Links</h6>
                            <div class="btn-group" role="group">
                                <a href="README.md" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    <i class="bi bi-file-text"></i> Documentation
                                </a>
                                <a href="TEST_PLAN.md" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    <i class="bi bi-clipboard-check"></i> Test Plan
                                </a>
                                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh Check
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
