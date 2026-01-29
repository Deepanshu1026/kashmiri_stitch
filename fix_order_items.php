<?php
include 'config/db_connect.php';

// Add size and color to order_items table
$conn->query("ALTER TABLE order_items ADD COLUMN size VARCHAR(50)");
$conn->query("ALTER TABLE order_items ADD COLUMN color VARCHAR(50)");

echo "Order Items table updated.\n";
?>
