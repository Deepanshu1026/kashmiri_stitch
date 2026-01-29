<?php
include 'config/db_connect.php';

echo "Table: products\n";
$result = $conn->query("DESCRIBE products");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>
