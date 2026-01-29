<?php
include 'config/db_connect.php';

$tables = ['products', 'reviews', 'product_variants']; // Checking for potential variant table too

foreach ($tables as $table) {
    echo "Table: $table\n";
    $result = $conn->query("DESCRIBE $table");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . "\n";
        }
    } else {
        echo "Table not found or error: " . $conn->error . "\n";
    }
    echo "\n";
}
?>
