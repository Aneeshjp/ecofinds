# EcoFinds - Sustainable Second-Hand Marketplace

EcoFinds is a comprehensive web application that serves as a sustainable second-hand marketplace, empowering users to buy and sell pre-owned goods while promoting environmental consciousness and circular economy principles.

## 🌱 Features

### Core Functionality
- **User Authentication**: Secure registration and login system with password hashing
- **User Profile Management**: Edit username and view account information
- **Product Management**: Create, edit, delete, and manage product listings
- **Product Browsing**: Browse products with search and category filtering
- **Shopping Cart**: Add items to cart, update quantities, and manage cart contents
- **Purchase System**: Complete purchases and track purchase history
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5

### Key Features
- **Category Filtering**: Filter products by predefined categories
- **Keyword Search**: Search products by title and description
- **Image Upload**: Support for product images with placeholder fallback
- **Purchase History**: View all previous purchases with environmental impact stats
- **Dashboard**: User dashboard with statistics and quick actions
- **Security**: Secure password hashing and session management

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **JavaScript**: Vanilla JavaScript with Fetch API
- **Icons**: Font Awesome 6
- **Server**: Apache (XAMPP)

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server
- XAMPP (recommended for local development)

## 🚀 Installation & Setup

### 1. Clone/Download the Project
```bash
# If using git
git clone <repository-url>
# Or download and extract the ZIP file
```

### 2. Database Setup
1. Start XAMPP and ensure Apache and MySQL are running
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `ecofinds`
4. Import the `database.sql` file to create tables and sample data

### 3. Configuration
1. Update database credentials in `config/database.php` if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'ecofinds');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### 4. File Permissions
Ensure the `uploads/` directory has write permissions:
```bash
chmod 755 uploads/
```

### 5. Access the Application
Open your browser and navigate to:
```
http://localhost/ecofinds/
```

## 📁 Project Structure

```
ecofinds/
├── config/
│   └── database.php          # Database configuration
├── includes/
│   ├── auth.php              # Authentication functions
│   ├── products.php          # Product management functions
│   └── cart.php              # Cart and purchase functions
├── uploads/
│   └── placeholder.svg       # Default product image
├── index.php                 # Homepage
├── register.php              # User registration
├── login.php                 # User login
├── dashboard.php             # User dashboard
├── profile.php               # User profile management
├── products.php              # Product browsing
├── product-detail.php        # Product details page
├── add-product.php           # Add new product
├── edit-product.php          # Edit product
├── delete-product.php        # Delete product
├── my-products.php           # User's products
├── cart.php                  # Shopping cart
├── add-to-cart.php           # Add to cart API
├── checkout.php              # Checkout API
├── purchase.php              # Purchase API
├── purchase-history.php      # Purchase history
├── database.sql              # Database schema
└── README.md                 # This file
```

## 🎯 User Journey

### For Buyers:
1. **Register/Login** → Create account or sign in
2. **Browse Products** → Search and filter products
3. **View Details** → See product information and seller details
4. **Add to Cart** → Add items to shopping cart
5. **Checkout** → Complete purchase
6. **View History** → Track purchase history

### For Sellers:
1. **Register/Login** → Create account or sign in
2. **Add Products** → Create product listings with images
3. **Manage Products** → Edit or delete listings
4. **View Dashboard** → Monitor sales and account activity
5. **Track Sales** → See which items have been sold

## 🔧 API Endpoints

### Cart Operations
- `POST /add-to-cart.php` - Add product to cart
- `POST /checkout.php` - Complete checkout process
- `POST /purchase.php` - Purchase single item

### Parameters
- `product_id` - Product ID (integer)
- `quantity` - Quantity (integer, for cart updates)
- `action` - Action type (string: 'update', 'remove', 'clear', 'checkout')

## 🎨 Design Features

- **Modern UI**: Clean, professional design with Bootstrap 5
- **Responsive**: Mobile-first approach with responsive grid system
- **Color Scheme**: Eco-friendly green and blue gradient theme
- **Icons**: Font Awesome icons throughout the interface
- **Animations**: Smooth hover effects and transitions
- **Accessibility**: Semantic HTML and proper contrast ratios

## 🔒 Security Features

- **Password Hashing**: Secure password storage using PHP's `password_hash()`
- **SQL Injection Prevention**: Prepared statements for all database queries
- **XSS Protection**: HTML escaping for all user inputs
- **Session Management**: Secure session handling
- **File Upload Security**: Image type and size validation

## 🌍 Environmental Impact

EcoFinds promotes sustainability by:
- **Extending Product Lifecycle**: Giving items a second life
- **Reducing Waste**: Preventing items from ending up in landfills
- **Conscious Consumption**: Encouraging thoughtful purchasing decisions
- **Community Building**: Connecting like-minded environmentally conscious users

## 🚀 Future Enhancements

Potential features for future development:
- **Messaging System**: Direct communication between buyers and sellers
- **Rating System**: User and product ratings
- **Payment Integration**: Online payment processing
- **Mobile App**: Native mobile application
- **Advanced Search**: More sophisticated filtering options
- **Social Features**: User profiles and social interactions
- **Analytics**: Sales and user behavior analytics

## 🐛 Troubleshooting

### Common Issues:

1. **Database Connection Error**
   - Check if MySQL is running in XAMPP
   - Verify database credentials in `config/database.php`

2. **Image Upload Issues**
   - Ensure `uploads/` directory has write permissions
   - Check file size limits in PHP configuration

3. **Session Issues**
   - Clear browser cookies and cache
   - Check PHP session configuration

4. **Page Not Found (404)**
   - Ensure Apache is running
   - Check file paths and URL rewriting

## 📞 Support

For support or questions:
- Check the troubleshooting section above
- Review the code comments for implementation details
- Ensure all requirements are met

## 📄 License

This project is created for educational purposes as part of a hackathon challenge. Feel free to use and modify for learning purposes.

---

**EcoFinds** - Empowering Sustainable Consumption through Second-Hand Marketplace 🌱
