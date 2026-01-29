<?php
session_start();
include 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please login to submit a review']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $productId = $_POST['product_id'];
    $rating = $_POST['rating'];
    $reviewText = $_POST['review_text']; // Assuming text area name is review_text
    
    // Basic Validation
    if (empty($productId) || empty($rating)) {
        echo json_encode(['status' => 'error', 'message' => 'Product and Rating are required']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $productId, $userId, $rating, $reviewText);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error submitting review: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
