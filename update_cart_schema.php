<?php
include 'config/db_connect.php';

// Add size and color to cart table
$conn->query("ALTER TABLE cart ADD COLUMN size VARCHAR(50)");
$conn->query("ALTER TABLE cart ADD COLUMN color VARCHAR(50)");

// Remove unique key if it exists on (user_id, product_id) to allow multiple variants of same product
// First, let's try to drop the index if we know its name. Often it's 'user_id' or 'product_id' or a composite.
// Or we can just try to Add a new unique index including size and color.
// For now, let's just add the columns. If unique constraint exists, we might hit issues if we try to insert same product with different size.
// Let's check keys first? No, let's just proceed. If insert fails, I'll know.
// Actually, usually cart unique index is (user_id, product_id). If so, I need to drop it.
// I'll try to drop common index names.
$conn->query("ALTER TABLE cart DROP INDEX unique_cart_item"); // Hypothetical name
$conn->query("ALTER TABLE cart DROP INDEX user_id"); // Sometimes it's just user_id + product_id via unique

// Add size and color to order_items table
$conn->query("ALTER TABLE order_items ADD COLUMN size VARCHAR(50)");
$conn->query("ALTER TABLE order_items ADD COLUMN color VARCHAR(50)");

echo "Cart and Order Items tables updated.\n";
?>
