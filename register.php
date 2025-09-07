<?php
/**
 * Registration Page for Velvet Vogue E-Commerce Website
 * Handles new user registration
 */

$pageTitle = "Register";
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $result = registerUser($username, $email, $password, 'customer');
        
        if ($result['success']) {
            $success = $result['message'] . ' You can now log in.';
            // Clear form data
            $_POST = [];
        } else {
            $error = $result['message'];
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <i class="bi bi-gem display-4 text-primary-custom"></i>
                        <h2 class="fw-bold text-primary-custom mt-2">Join Velvet Vogue</h2>
                        <p class="text-muted">Create your account and start shopping</p>
                    </div>
                    
                    <!-- Error/Success Messages -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Registration Form -->
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Username *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                       placeholder="Choose a username"
                                       pattern="[a-zA-Z0-9_]{3,20}"
                                       required>
                                <div class="invalid-feedback">
                                    Username must be 3-20 characters (letters, numbers, underscore only).
                                </div>
                                <div class="form-text">
                                    3-20 characters, letters, numbers, and underscore only.
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email Address *
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       placeholder="Enter your email"
                                       required>
                                <div class="invalid-feedback">
                                    Please enter a valid email address.
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password *
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Create a password"
                                           minlength="6"
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    Password must be at least 6 characters long.
                                </div>
                                <div class="form-text">
                                    Minimum 6 characters.
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Confirm Password *
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Confirm your password"
                                       required>
                                <div class="invalid-feedback">
                                    Please confirm your password.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="agreeTerms" 
                                       name="agree_terms" 
                                       required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the 
                                    <a href="#" class="text-primary-custom">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-primary-custom">Privacy Policy</a> *
                                </label>
                                <div class="invalid-feedback">
                                    You must agree to the terms and conditions.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="newsletter" 
                                       name="newsletter">
                                <label class="form-check-label" for="newsletter">
                                    Subscribe to our newsletter for updates and exclusive offers
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                            <i class="bi bi-person-plus"></i> Create Account
                        </button>
                    </form>
                    
                    <!-- Divider -->
                    <div class="text-center my-4">
                        <span class="text-muted">Already have an account?</span>
                    </div>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <a href="login.php" class="btn btn-outline-primary-custom btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In Instead
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Benefits Section -->
            <div class="row mt-5">
                <div class="col-md-4 text-center mb-3">
                    <i class="bi bi-truck display-6 text-primary-custom"></i>
                    <h6 class="mt-2">Free Shipping</h6>
                    <small class="text-muted">On orders over $50</small>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="bi bi-arrow-clockwise display-6 text-primary-custom"></i>
                    <h6 class="mt-2">Easy Returns</h6>
                    <small class="text-muted">30-day return policy</small>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="bi bi-headset display-6 text-primary-custom"></i>
                    <h6 class="mt-2">24/7 Support</h6>
                    <small class="text-muted">Customer service</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
});

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

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
