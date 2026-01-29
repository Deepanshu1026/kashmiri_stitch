<?php
include 'config/db_connect.php';

echo "Table: cart\n";
$result = $conn->query("DESCRIBE cart");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}

echo "\nTable: order_items\n";
$result = $conn->query("DESCRIBE order_items");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>
