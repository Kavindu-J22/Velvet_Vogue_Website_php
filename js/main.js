/**
 * Main JavaScript file for Velvet Vogue E-Commerce Website
 * Handles interactive features and AJAX requests
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize cart functionality
    initializeCart();
    
    // Initialize product interactions
    initializeProductInteractions();
    
    // Initialize form validations
    initializeFormValidations();
});

/**
 * Initialize shopping cart functionality
 */
function initializeCart() {
    // Add to cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = this.dataset.quantity || 1;
            addToCart(productId, quantity);
        });
    });
    
    // Quantity update buttons in cart
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const action = this.dataset.action;
            const quantityInput = document.querySelector(`input[data-cart-id="${cartId}"]`);
            
            if (quantityInput) {
                let currentQuantity = parseInt(quantityInput.value);
                
                if (action === 'increase') {
                    currentQuantity++;
                } else if (action === 'decrease' && currentQuantity > 1) {
                    currentQuantity--;
                }
                
                quantityInput.value = currentQuantity;
                updateCartQuantity(cartId, currentQuantity);
            }
        });
    });
    
    // Remove from cart buttons
    document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const cartId = this.dataset.cartId;
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                removeFromCart(cartId);
            }
        });
    });
}

/**
 * Add item to cart via AJAX
 */
function addToCart(productId, quantity = 1) {
    showLoading();
    
    fetch('ajax/add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showAlert('success', data.message);
            updateCartBadge();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('danger', 'An error occurred. Please try again.');
    });
}

/**
 * Update cart item quantity
 */
function updateCartQuantity(cartId, quantity) {
    fetch('ajax/update-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart total display
            updateCartDisplay();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred. Please try again.');
    });
}

/**
 * Remove item from cart
 */
function removeFromCart(cartId) {
    fetch('ajax/remove-from-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the cart item from DOM
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`).closest('.cart-item');
            if (cartItem) {
                cartItem.remove();
            }
            
            updateCartBadge();
            updateCartDisplay();
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred. Please try again.');
    });
}

/**
 * Update cart badge in navigation
 */
function updateCartBadge() {
    fetch('ajax/get-cart-count.php')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.querySelector('.cart-badge');
        if (data.count > 0) {
            if (cartBadge) {
                cartBadge.textContent = data.count;
            } else {
                // Create badge if it doesn't exist
                const cartLink = document.querySelector('a[href="cart.php"]');
                if (cartLink) {
                    const badge = document.createElement('span');
                    badge.className = 'cart-badge';
                    badge.textContent = data.count;
                    cartLink.appendChild(badge);
                }
            }
        } else if (cartBadge) {
            cartBadge.remove();
        }
    })
    .catch(error => {
        console.error('Error updating cart badge:', error);
    });
}

/**
 * Update cart display (totals, etc.)
 */
function updateCartDisplay() {
    // This would be implemented to update cart totals on the cart page
    const cartTotalElement = document.querySelector('.cart-total');
    if (cartTotalElement) {
        fetch('ajax/get-cart-total.php')
        .then(response => response.json())
        .then(data => {
            cartTotalElement.textContent = '$' + data.total.toFixed(2);
        })
        .catch(error => {
            console.error('Error updating cart total:', error);
        });
    }
}

/**
 * Initialize product interactions
 */
function initializeProductInteractions() {
    // Product image zoom or gallery functionality could go here
    
    // Product filtering
    const filterForm = document.querySelector('#product-filter-form');
    if (filterForm) {
        filterForm.addEventListener('change', function() {
            this.submit();
        });
    }
}

/**
 * Initialize form validations
 */
function initializeFormValidations() {
    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    });
}

/**
 * Show loading spinner
 */
function showLoading() {
    const existingSpinner = document.querySelector('.loading-spinner');
    if (!existingSpinner) {
        const spinner = document.createElement('div');
        spinner.className = 'loading-spinner position-fixed top-50 start-50 translate-middle';
        spinner.innerHTML = '<div class="spinner"></div>';
        spinner.style.zIndex = '9999';
        document.body.appendChild(spinner);
    }
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    const spinner = document.querySelector('.loading-spinner');
    if (spinner) {
        spinner.remove();
    }
}

/**
 * Show alert message
 */
function showAlert(type, message) {
    const alertContainer = document.querySelector('.alert-container') || document.querySelector('.container');
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.insertBefore(alert, alertContainer.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}
