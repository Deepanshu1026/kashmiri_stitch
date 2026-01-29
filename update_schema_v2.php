<?php
include 'config/db_connect.php';

// Add sizes and colors to products table if not exists
$conn->query("ALTER TABLE products ADD COLUMN available_sizes VARCHAR(255) DEFAULT 'S,M,L,XL,XXL'");
$conn->query("ALTER TABLE products ADD COLUMN available_colors VARCHAR(255) DEFAULT 'red,green,blue,black'");

// Update existing products to have some values if they are null (though DEFAULT handles new inserts, existing rows might need it if we didn't use NOT NULL)
$conn->query("UPDATE products SET available_sizes = 'S,M,L,XL' WHERE available_sizes IS NULL");
$conn->query("UPDATE products SET available_colors = 'red,blue,black' WHERE available_colors IS NULL");

// Create reviews table
$sql_reviews = "CREATE TABLE IF NOT EXISTS reviews (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT(6) UNSIGNED NOT NULL,
    user_id INT(6) UNSIGNED NOT NULL,
    rating INT(1) NOT NULL,
    review_title VARCHAR(100),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_reviews) === TRUE) {
    echo "Reviews table created successfully.\n";
} else {
    echo "Error creating reviews table: " . $conn->error . "\n";
}

echo "Database updated successfully.\n";
?>
