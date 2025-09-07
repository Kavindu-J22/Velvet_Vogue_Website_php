    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="bi bi-gem"></i> Velvet Vogue</h5>
                    <p class="mb-3">Your premier destination for elegant and stylish clothing. Discover the latest fashion trends and timeless classics.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-youtube fs-4"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Shop</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="products.php">All Products</a></li>
                        <li class="mb-2"><a href="category.php?id=1">Men's Clothing</a></li>
                        <li class="mb-2"><a href="category.php?id=2">Women's Clothing</a></li>
                        <li class="mb-2"><a href="category.php?id=5">Accessories</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#contact">Contact Us</a></li>
                        <li class="mb-2"><a href="#shipping">Shipping Info</a></li>
                        <li class="mb-2"><a href="#returns">Returns</a></li>
                        <li class="mb-2"><a href="#size-guide">Size Guide</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#about">About Us</a></li>
                        <li class="mb-2"><a href="#careers">Careers</a></li>
                        <li class="mb-2"><a href="#privacy">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#terms">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Connect</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-envelope"></i> 
                            <a href="mailto:info@velvetvogue.com">info@velvetvogue.com</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone"></i> 
                            <a href="tel:+1234567890">+1 (234) 567-8900</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-geo-alt"></i> 
                            123 Fashion Street, Style City
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: var(--secondary-color);">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Velvet Vogue. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small>Designed with <i class="bi bi-heart-fill text-danger"></i> for fashion lovers</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="js/main.js"></script>
    
    <!-- Additional scripts -->
    <?php if (isset($additionalScripts)) echo $additionalScripts; ?>
</body>
</html>
