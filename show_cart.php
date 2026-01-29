<?php
include 'config/db_connect.php';
$result = $conn->query("SHOW CREATE TABLE cart");
$row = $result->fetch_assoc();
echo $row['Create Table'];
?>
