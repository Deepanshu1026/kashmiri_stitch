<?php
session_start();
include 'config/db_connect.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $cart_id = $conn->real_escape_string($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $conn->query("DELETE FROM cart WHERE id='$cart_id' AND user_id='$user_id'");
}
header("Location: cart.php");
exit();
?>
