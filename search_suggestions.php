<?php
include 'config/db_connect.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$search = $conn->real_escape_string($query);
$category_filter = "";

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    $category_filter = " AND category = '$category'";
}

$sql = "SELECT id, title, image, price, category FROM products 
        WHERE (title LIKE '%$search%' OR description LIKE '%$search%') 
        $category_filter
        LIMIT 5";

$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
?>
