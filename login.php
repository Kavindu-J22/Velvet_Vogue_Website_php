<?php
/**
 * Login Page for Velvet Vogue E-Commerce Website
 * Handles user authentication
 */

$pageTitle = "Login";
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $result = loginUser($username, $password);
        
        if ($result['success']) {
            // Redirect to intended page or dashboard
            $redirect = $_GET['redirect'] ?? 'index.php';
            header("Location: " . $redirect);
            exit();
        } else {
            $error = $result['message'];
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <i class="bi bi-gem display-4 text-primary-custom"></i>
                        <h2 class="fw-bold text-primary-custom mt-2">Welcome Back</h2>
                        <p class="text-muted">Sign in to your Velvet Vogue account</p>
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
                    
                    <!-- Login Form -->
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person"></i> Username or Email
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   placeholder="Enter your username or email"
                                   required>
                            <div class="invalid-feedback">
                                Please enter your username or email.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Please enter your password.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-primary-custom text-decoration-none">
                                Forgot password?
                            </a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </button>
                    </form>
                    
                    
                    
                    <!-- Divider -->
                    <div class="text-center my-4">
                        <span class="text-muted">Don't have an account?</span>
                    </div>
                    
                    <!-- Register Link -->
                    <div class="text-center">
                        <a href="register.php" class="btn btn-outline-primary-custom btn-lg w-100">
                            <i class="bi bi-person-plus"></i> Create New Account
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="text-center mt-4">
                <p class="text-muted">
                    <small>
                        By signing in, you agree to our 
                        <a href="#" class="text-primary-custom">Terms of Service</a> and 
                        <a href="#" class="text-primary-custom">Privacy Policy</a>
                    </small>
                </p>
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
