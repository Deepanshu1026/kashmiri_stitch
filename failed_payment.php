<?php
ob_start();
session_start();
header('Content-Type: application/json');

ob_clean();

include 'config/db_connect.php';
include 'config/send_email.php';

$error_description = $_POST['error_description'] ?? 'Unknown Error';
$razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
$razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';

if (empty($razorpay_order_id)) {
    echo json_encode(['status' => 'failure', 'message' => 'Order ID missing']);
    exit;
}

// Fetch User Details using Order ID
$stmt = $conn->prepare("SELECT email, firstname, lastname FROM orders WHERE razorpay_order_id = ?");
$stmt->bind_param("s", $razorpay_order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order_data = $result->fetch_assoc();
    $user_email = $order_data['email'];
    $user_name = ($order_data['firstname'] ?? '') . ' ' . ($order_data['lastname'] ?? '');

    // Update Order Status
    $updateStmt = $conn->prepare("UPDATE orders SET status = 'failed' WHERE razorpay_order_id = ?");
    $updateStmt->bind_param("s", $razorpay_order_id);
    $updateStmt->execute();

    // Send Failure Email
    $subject = "Payment Failed - Kashmiri Stitch";
    $message = "<h1>Payment Failed</h1>
                <p>Dear $user_name,</p>
                <p>We noticed that your payment for order ID <strong>$razorpay_order_id</strong> was not successful.</p>
                <p><strong>Reason:</strong> $error_description</p>
                <p>If you have any questions or need assistance, please contact our support team.</p>";
    
    $emailResult = sendEmail($user_email, $user_name, $subject, $message);

    echo json_encode(['status' => 'success', 'message' => 'Failure recorded and email sent', 'email_status' => $emailResult]);
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Order not found']);
}
?>
