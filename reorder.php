<?php
session_start();
include 'config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to reorder']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];

    // Validated order belongs to user
    $check = $conn->query("SELECT id FROM orders WHERE id='$order_id' AND user_id='$user_id'");
    if($check->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Order not found']);
        exit();
    }

    // Fetch items
    $items = $conn->query("SELECT product_id, quantity, size, color FROM order_items WHERE order_id='$order_id'");
    if($items->num_rows > 0) {
        $count = 0;
        while($item = $items->fetch_assoc()) {
            $pid = $item['product_id'];
            $qty = $item['quantity'];
            $size = $item['size'] ?? '';
            $color = $item['color'] ?? '';
            
            // Check if product still exists
            $p_check = $conn->query("SELECT id FROM products WHERE id='$pid'");
            if($p_check->num_rows > 0) {
                 // Insert or Update Cart
                 $checkCart = $conn->query("SELECT id FROM cart WHERE user_id='$user_id' AND product_id='$pid' AND size='$size' AND color='$color'");
                 if($checkCart->num_rows > 0) {
                     $conn->query("UPDATE cart SET quantity = quantity + $qty WHERE user_id='$user_id' AND product_id='$pid' AND size='$size' AND color='$color'");
                 } else {
                     $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size, color) VALUES (?, ?, ?, ?, ?)");
                     $stmt->bind_param("iiiss", $user_id, $pid, $qty, $size, $color);
                     $stmt->execute();
                 }
                 $count++;
            }
        }
        echo json_encode(['status' => 'success', 'message' => "$count items added to cart"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No items in this order']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
