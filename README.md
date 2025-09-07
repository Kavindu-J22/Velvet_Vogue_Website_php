# Velvet Vogue E-Commerce Website

A fully functional, responsive e-commerce website built with PHP, MySQL, HTML5, CSS3, JavaScript, and Bootstrap 5. This project demonstrates a complete online clothing store with user authentication, product management, shopping cart functionality, and order processing.

## 🌟 Features

### Customer Features
- **User Registration & Authentication** - Secure user accounts with password hashing
- **Product Browsing** - View products by category with filtering and sorting options
- **Product Search** - Advanced search functionality with highlighted results
- **Shopping Cart** - Add, update, and remove items with real-time calculations
- **Checkout Process** - Complete order processing with shipping and tax calculations
- **Responsive Design** - Mobile-friendly interface using Bootstrap 5
- **Order Confirmation** - Professional order success page with details

### Admin Features
- **Admin Dashboard** - Protected admin panel for store management
- **Product Management** - Add, edit, and delete products with image uploads
- **Inventory Tracking** - Stock quantity management with low stock alerts
- **Order Overview** - View and manage customer orders
- **Statistics Dashboard** - Key metrics and store performance indicators

### Technical Features
- **Secure Database Operations** - PDO with prepared statements
- **Session Management** - Secure user sessions and authentication
- **Image Upload System** - Product image handling with validation
- **AJAX Integration** - Dynamic cart updates without page refresh
- **Error Handling** - Comprehensive error logging and user feedback
- **SQL Injection Protection** - Parameterized queries throughout

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.3.0
- **Icons**: Bootstrap Icons
- **Server**: Apache (XAMPP recommended)

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/) (includes Apache, MySQL, PHP)
- Web browser (Chrome, Firefox, Safari, Edge)
- Text editor or IDE (VS Code, PhpStorm, etc.)

## 🚀 Installation & Setup

### Step 1: Download and Setup XAMPP
1. Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/)
2. Start the XAMPP Control Panel
3. Start **Apache** and **MySQL** services

### Step 2: Project Setup
1. Navigate to your XAMPP installation directory
2. Go to the `htdocs` folder (usually `C:\xampp\htdocs` on Windows)
3. Create a new folder named `velvet_vogue`
4. Copy all project files into this folder

### Step 3: Database Setup
1. Open your web browser and go to `http://localhost/phpmyadmin`
2. Click on "Import" tab
3. Choose the `database_schema.sql` file from the project root
4. Click "Go" to execute the SQL script
5. The database `velvet_vogue_db` will be created with sample data

### Step 4: Configuration
1. Open `includes/config.php`
2. Verify the database configuration:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'velvet_vogue_db');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Default XAMPP password is empty
   ```

### Step 5: File Permissions
1. Ensure the `uploads/` directory has write permissions
2. On Windows, this is usually automatic
3. On Linux/Mac, run: `chmod 755 uploads/`

### Step 6: Access the Website
1. Open your web browser
2. Navigate to `http://localhost/velvet_vogue`
3. The homepage should load successfully

## 👤 Default Login Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- **Access**: Full admin dashboard and product management

### Customer Account
- Register a new account using the registration page
- Or create additional accounts as needed

## 📁 Project Structure

```
velvet_vogue/
├── includes/           # PHP backend logic
│   ├── config.php     # Database configuration
│   ├── auth.php       # Authentication functions
│   ├── products.php   # Product management functions
│   ├── cart.php       # Shopping cart functions
│   ├── header.php     # Common header component
│   └── footer.php     # Common footer component
├── ajax/              # AJAX endpoints
│   ├── add-to-cart.php
│   ├── update-cart.php
│   ├── remove-from-cart.php
│   ├── get-cart-count.php
│   └── get-cart-total.php
├── css/               # Stylesheets
│   └── style.css      # Custom CSS styles
├── js/                # JavaScript files
│   └── main.js        # Main JavaScript functionality
├── uploads/           # Product images (auto-created)
├── images/            # Static website images
├── index.php          # Homepage
├── products.php       # Product listing page
├── product-detail.php # Individual product page
├── category.php       # Category-filtered products
├── search.php         # Search results page
├── cart.php           # Shopping cart page
├── checkout.php       # Checkout process
├── order-success.php  # Order confirmation
├── login.php          # User login page
├── register.php       # User registration page
├── admin-dashboard.php # Admin management panel
├── logout.php         # Logout functionality
├── database_schema.sql # Database structure and sample data
└── README.md          # This file
```

## 🎯 Usage Guide

### For Customers
1. **Browse Products**: Visit the homepage and explore featured products
2. **Search**: Use the search bar to find specific items
3. **Filter**: Use category filters and sorting options
4. **Add to Cart**: Click "Add to Cart" on any product (requires login)
5. **Checkout**: Review cart and complete the checkout process
6. **Order Confirmation**: Receive order confirmation with details

### For Administrators
1. **Login**: Use admin credentials to access the dashboard
2. **Add Products**: Use the admin dashboard to add new products
3. **Manage Inventory**: Update stock quantities and product details
4. **View Statistics**: Monitor store performance and inventory levels
5. **Delete Products**: Remove products that are no longer available

## 🔧 Customization

### Adding New Categories
1. Access phpMyAdmin (`http://localhost/phpmyadmin`)
2. Navigate to `velvet_vogue_db` > `categories` table
3. Insert new category records
4. Categories will automatically appear in navigation

### Modifying Styles
1. Edit `css/style.css` for custom styling
2. Modify CSS variables in `:root` for color scheme changes
3. Bootstrap classes can be overridden as needed

### Adding New Features
1. Create new PHP files following the existing structure
2. Include necessary authentication checks
3. Use the established database connection methods
4. Follow the existing error handling patterns

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Ensure MySQL is running in XAMPP
- Verify database credentials in `config.php`
- Check if `velvet_vogue_db` database exists

**Images Not Displaying**
- Check if `uploads/` directory exists and has write permissions
- Verify image file paths in the database
- Ensure uploaded images are in supported formats

**Cart Not Working**
- Check if JavaScript is enabled in browser
- Verify AJAX endpoints are accessible
- Check browser console for JavaScript errors

**Admin Dashboard Access Denied**
- Ensure you're logged in with admin credentials
- Check user_type in database is set to 'admin'
- Clear browser cache and cookies

## 📝 Testing

### Manual Testing Checklist
- [ ] User registration and login
- [ ] Product browsing and filtering
- [ ] Search functionality
- [ ] Add/remove items from cart
- [ ] Checkout process
- [ ] Admin product management
- [ ] Responsive design on mobile devices

### Test Data
The database includes sample products and an admin account for testing purposes.

## 🔒 Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with `htmlspecialchars()`
- Session-based authentication
- Admin access control
- File upload validation
- CSRF protection considerations

## 📈 Future Enhancements

- Order tracking system
- Email notifications
- Payment gateway integration
- Product reviews and ratings
- Wishlist functionality
- Advanced inventory management
- Multi-language support
- SEO optimization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is created for educational purposes. Feel free to use and modify as needed.

## 📞 Support

For questions or issues:
- Check the troubleshooting section above
- Review the code comments for implementation details
- Test with the provided sample data

---

**Velvet Vogue** - Your Premier Fashion Destination 💎
