# Kashmiri Stitch

**Kashmiri Stitch** is a full-featured e-commerce web application designed to showcase and sell premium Kashmiri clothing and accessories. Built with a robust PHP backend and a dynamic frontend, it offers a seamless shopping experience with secure payments, user authentication, and a responsive design.

## 🚀 Features

### User Features
- **Browse & Shop**: Extensive product catalog with categories for Men, Women, and Kids.
- **User Authentication**: Secure Login and Signup, including **Google OAuth** integration.
- **Shopping Cart**: Real-time cart management with quantity updates.
- **Wishlist**: Save favorite items for later.
- **Secure Checkout**: Integrated **Razorpay** payment gateway for safe transactions.
- **Order History**: Track past orders and view details.
- **Product Reviews**: Read and write reviews for products.
- **Blog**: Latest news and fashion trends.
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices.

### Admin Features
- **Dashboard**: Overview of sales and metrics.
- **Product Management**: Add, edit, and delete products (images, prices, categories).
- **Order Management**: View and process customer orders.

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript (Swiper.js, WOW.js, Bootstrap Grid).
- **Backend**: PHP (Vanilla).
- **Database**: MySQL.
- **Dependencies**: Composer.
- **Payment Gateway**: Razorpay.
- **Email Service**: Brevo (formerly Sendinblue) via `sendinblue/api-v3-sdk`.

## 📦 Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Deepanshu1026/kashmiri_stitch.git
   cd kashmiri_stitch
   ```

2. **Install Dependencies**
   Ensure you have [Composer](https://getcomposer.org/) installed.
   ```bash
   composer install
   ```

3. **Database Configuration**
   - The application expects a database configuration file.
   - Create a file named `credentials.php` in the `config/` directory.
   - Define your database constants (e.g., `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`).
   - *Note: The application automatically attempts to create the database and necessary tables (`users`, `wishlist`, etc.) via `config/db_connect.php` if they don't exist.*

4. **Web Server Setup**
   - Place the project in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Start Apache and MySQL services.

5. **Run the Application**
   - Open your browser and navigate to `http://localhost/kashmiri_stitch`.

## 📂 Project Structure

- **`/admin`**: Admin panel files.
- **`/assets`**: Static assets (CSS, JS, Images).
- **`/config`**: Database connections and configuration.
- **`/vendor`**: Composer dependencies.
- **`*.php`**: Core application files (Shop, Cart, Checkout, etc.).

## 🔑 Key Integrations

- **Razorpay**: Handles all payment processing. Ensure valid API keys are configured in your payment scripts.
- **Google Login**: Requires a configured Google Cloud Console project with valid redirect URIs.
- **Brevo/Sendinblue**: Used for sending transactional emails and newsletters.

## 🤝 Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

---
*Developed by Deepanshu*
