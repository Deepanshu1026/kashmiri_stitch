<?php
session_start();
include 'config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    if ($quantity < 1) {
        // If quantity is less than 1, we might want to remove it or set to 1. 
        // Typically set to 1 or error. Let's set to 1.
        $quantity = 1;
    }

    // Verify ownership
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
