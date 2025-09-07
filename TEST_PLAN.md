# Velvet Vogue E-Commerce Website - Test Plan

## Test Environment Setup
- **Server**: XAMPP (Apache + MySQL + PHP)
- **Database**: velvet_vogue_db with sample data
- **Browsers**: Chrome, Firefox, Safari, Edge
- **Devices**: Desktop, Tablet, Mobile

## Test Accounts
- **Admin**: username: `admin`, password: `admin123`
- **Customer**: Create new accounts during testing

---

## 1. User Authentication Tests

### Test Case 1.1: User Registration
**Objective**: Verify users can create new accounts successfully

**Test Steps**:
1. Navigate to `http://localhost/velvet_vogue/register.php`
2. Fill in all required fields with valid data
3. Click "Create Account" button
4. Verify success message appears
5. Check database for new user record

**Expected Result**: User account created successfully, redirected to login page
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 1.2: User Login - Valid Credentials
**Objective**: Verify users can log in with correct credentials

**Test Steps**:
1. Navigate to login page
2. Enter valid username/email and password
3. Click "Sign In" button
4. Verify successful login and redirection

**Expected Result**: User logged in successfully, redirected to homepage with user menu visible
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 1.3: User Login - Invalid Credentials
**Objective**: Verify system rejects invalid login attempts

**Test Steps**:
1. Navigate to login page
2. Enter invalid username or password
3. Click "Sign In" button
4. Verify error message displayed

**Expected Result**: Error message "Invalid username or password" displayed
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 1.4: Admin Access Control
**Objective**: Verify admin dashboard is protected

**Test Steps**:
1. Log in as regular customer
2. Navigate to `admin-dashboard.php`
3. Verify access is denied
4. Log in as admin and verify access granted

**Expected Result**: Regular users redirected away, admin users can access dashboard
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 2. Product Management Tests

### Test Case 2.1: View Products Homepage
**Objective**: Verify products display correctly on homepage

**Test Steps**:
1. Navigate to homepage
2. Verify featured products section loads
3. Check product images, names, and prices display
4. Verify "Add to Cart" buttons appear for logged-in users

**Expected Result**: Products display with correct information and functional buttons
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 2.2: Product Detail Page
**Objective**: Verify individual product pages work correctly

**Test Steps**:
1. Click on any product from homepage or product listing
2. Verify product detail page loads
3. Check all product information displays correctly
4. Test quantity selector functionality
5. Test "Add to Cart" button

**Expected Result**: Product details display correctly, quantity controls work, cart functionality works
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 2.3: Category Filtering
**Objective**: Verify products can be filtered by category

**Test Steps**:
1. Navigate to products page
2. Select different categories from filter
3. Verify only products from selected category display
4. Test "All Categories" option

**Expected Result**: Products filter correctly by category
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 2.4: Product Search
**Objective**: Verify search functionality works correctly

**Test Steps**:
1. Use search bar in navigation
2. Search for "dress", "suit", "black", etc.
3. Verify relevant results appear
4. Test search with no results
5. Verify search term highlighting

**Expected Result**: Search returns relevant results, highlights search terms, handles no results gracefully
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 3. Shopping Cart Tests

### Test Case 3.1: Add Item to Cart
**Objective**: Verify items can be added to shopping cart

**Test Steps**:
1. Log in as customer
2. Navigate to any product
3. Click "Add to Cart" button
4. Verify success message appears
5. Check cart badge updates in navigation
6. Navigate to cart page and verify item appears

**Expected Result**: Item added to cart successfully, cart count updates, item visible in cart
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 3.2: Update Cart Quantity
**Objective**: Verify cart item quantities can be modified

**Test Steps**:
1. Add item to cart
2. Navigate to cart page
3. Use quantity controls to increase/decrease quantity
4. Verify total price updates automatically
5. Test quantity limits (stock availability)

**Expected Result**: Quantities update correctly, totals recalculate, stock limits enforced
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 3.3: Remove Item from Cart
**Objective**: Verify items can be removed from cart

**Test Steps**:
1. Add multiple items to cart
2. Navigate to cart page
3. Click "Remove" button on one item
4. Confirm removal in popup
5. Verify item disappears and totals update

**Expected Result**: Item removed successfully, cart totals update, confirmation required
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 3.4: Cart Persistence
**Objective**: Verify cart contents persist across sessions

**Test Steps**:
1. Add items to cart
2. Log out
3. Log back in
4. Check if cart contents are preserved

**Expected Result**: Cart contents remain after logout/login cycle
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 4. Checkout Process Tests

### Test Case 4.1: Checkout with Valid Information
**Objective**: Verify complete checkout process works

**Test Steps**:
1. Add items to cart
2. Navigate to checkout page
3. Fill in all shipping information
4. Select payment method
5. Click "Place Order"
6. Verify order confirmation page appears

**Expected Result**: Order processed successfully, confirmation page shows order details
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 4.2: Checkout Validation
**Objective**: Verify checkout form validation works

**Test Steps**:
1. Navigate to checkout with items in cart
2. Try to submit form with missing required fields
3. Verify validation messages appear
4. Test with invalid data formats

**Expected Result**: Form validation prevents submission with invalid/missing data
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 4.3: Order Total Calculation
**Objective**: Verify order totals calculate correctly

**Test Steps**:
1. Add items to cart with different quantities
2. Navigate to checkout
3. Verify subtotal, shipping, tax, and total calculations
4. Test free shipping threshold ($50+)

**Expected Result**: All calculations are accurate, free shipping applies correctly
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 4.4: Stock Reduction After Order
**Objective**: Verify product stock decreases after successful order

**Test Steps**:
1. Note current stock of a product
2. Place order including that product
3. Check product stock after order completion
4. Verify stock reduced by ordered quantity

**Expected Result**: Product stock decreases by ordered quantity
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 5. Admin Dashboard Tests

### Test Case 5.1: Add New Product
**Objective**: Verify admin can add new products

**Test Steps**:
1. Log in as admin
2. Navigate to admin dashboard
3. Fill in product form with valid data
4. Upload product image
5. Submit form
6. Verify product appears in product list

**Expected Result**: New product added successfully, appears in listings
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 5.2: Delete Product
**Objective**: Verify admin can delete products

**Test Steps**:
1. Log in as admin
2. Navigate to admin dashboard
3. Click delete button on a product
4. Confirm deletion
5. Verify product removed from listings

**Expected Result**: Product deleted successfully, no longer appears in listings
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 5.3: Image Upload Validation
**Objective**: Verify image upload restrictions work

**Test Steps**:
1. Try uploading non-image file
2. Try uploading oversized image (>5MB)
3. Try uploading valid image
4. Verify appropriate responses for each

**Expected Result**: Invalid files rejected with error messages, valid files accepted
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 6. Responsive Design Tests

### Test Case 6.1: Mobile Responsiveness
**Objective**: Verify website works on mobile devices

**Test Steps**:
1. Open website on mobile device or use browser dev tools
2. Test navigation menu (hamburger menu)
3. Verify product grids adapt to screen size
4. Test form inputs and buttons
5. Check cart and checkout process

**Expected Result**: Website fully functional and properly formatted on mobile
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 6.2: Tablet Responsiveness
**Objective**: Verify website works on tablet devices

**Test Steps**:
1. Test on tablet or simulate tablet viewport
2. Verify layout adapts appropriately
3. Test touch interactions
4. Check product grid layouts

**Expected Result**: Website optimized for tablet viewing and interaction
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 7. Security Tests

### Test Case 7.1: SQL Injection Prevention
**Objective**: Verify database queries are protected

**Test Steps**:
1. Try entering SQL injection attempts in search box
2. Test login form with SQL injection strings
3. Verify no database errors or unauthorized access

**Expected Result**: SQL injection attempts blocked, no database compromise
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

### Test Case 7.2: XSS Prevention
**Objective**: Verify cross-site scripting protection

**Test Steps**:
1. Try entering JavaScript code in form fields
2. Check if scripts execute or are properly escaped
3. Test in product names, descriptions, etc.

**Expected Result**: JavaScript code displayed as text, not executed
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## 8. Performance Tests

### Test Case 8.1: Page Load Times
**Objective**: Verify pages load within acceptable time

**Test Steps**:
1. Measure load times for key pages (homepage, products, cart)
2. Test with multiple products and images
3. Check database query performance

**Expected Result**: Pages load within 3 seconds under normal conditions
**Actual Result**: ________________
**Status**: [ ] Pass [ ] Fail

---

## Test Summary

**Total Test Cases**: 24
**Passed**: ___
**Failed**: ___
**Pass Rate**: ___%

## Issues Found
1. ________________________________
2. ________________________________
3. ________________________________

## Recommendations
1. ________________________________
2. ________________________________
3. ________________________________

**Tester**: ________________
**Date**: ________________
**Environment**: ________________
