<?php
session_start();
include 'config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    
    // Check if user actually exists in DB (Handle Stale Session)
    $stmtUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    if ($stmtUser->get_result()->num_rows === 0) {
        session_destroy();
        header("Location: login.php?error=session_expired");
        exit();
    }

    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($quantity < 1) $quantity = 1;

    // Check if product exists
    $checkProduct = $conn->query("SELECT * FROM products WHERE id='$product_id'");
    if ($checkProduct->num_rows == 0) {
        die("Product not found.");
    }

    // Check if item already in cart
    $checkCart = $conn->query("SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
    
    if ($checkCart->num_rows > 0) {
        // Update quantity
        $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id='$user_id' AND product_id='$product_id'");
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }

    // Redirect back or to cart
    header("Location: cart.php");
    exit();
} else {
    // If accessed directly without POST (e.g. via simple link), perform simple add (qty 1)
    if (isset($_GET['id'])) {
        $user_id = $_SESSION['user_id'];
        $product_id = $_GET['id'];
        $quantity = 1;

        // Check/Add logic duplications... for simplicity in this turn I'll just redirect or handle simple add.
         // Check if item already in cart
        $checkCart = $conn->query("SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
        
        if ($checkCart->num_rows > 0) {
            $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id='$user_id' AND product_id='$product_id'");
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
        }
        header("Location: cart.php");
        exit();
    }
}
?>
