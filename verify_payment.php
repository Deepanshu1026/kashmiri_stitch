<?php
ob_start();
session_start();
header('Content-Type: application/json');

ob_clean(); // Clean any previous output

include 'config/db_connect.php';
include 'config/razorpay_config.php';
include 'config/send_email.php'; // Include email helper

$success = false;
$error = "Payment Failed";
$email_sent = false;

if (isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id']) && isset($_POST['razorpay_signature'])) {
    
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];

    // Fetch User Details for Email
    $stmt_order = $conn->prepare("SELECT email, firstname, lastname FROM orders WHERE razorpay_order_id = ?");
    $stmt_order->bind_param("s", $razorpay_order_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();
    $order_data = $result_order->fetch_assoc();
    
    $user_email = $order_data['email'] ?? '';
    $user_name = ($order_data['firstname'] ?? '') . ' ' . ($order_data['lastname'] ?? '');

    // 1. Verify Signature
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, RAZORPAY_KEY_SECRET);

    if ($generated_signature == $razorpay_signature) {
        
        // 1.1 Strong Verification: Call Razorpay API to check status
        $ch = curl_init();
        $url = "https://api.razorpay.com/v1/payments/" . $razorpay_payment_id;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ":" . RAZORPAY_KEY_SECRET);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = "Verification Failed: Internet Connection Error";
             // Send Failure Email
             if($user_email) {
                $subject = "Payment Failed - Kashmiri Stitch";
                $message = "<h1>Payment Failed</h1><p>Dear $user_name,</p><p>Your payment for order ID $razorpay_order_id has failed due to connection error. Please try again.</p>";
                sendEmail($user_email, $user_name, $subject, $message);
            }
        } else {
            $payment_data = json_decode($response, true);
            curl_close($ch);

            if ($http_status === 200 && isset($payment_data['status']) && $payment_data['status'] === 'captured') {
                 $success = true;
            
                // 2. Update Order Status in DB
                $stmt = $conn->prepare("UPDATE orders SET status = 'captured', razorpay_payment_id = ? WHERE razorpay_order_id = ?");
                $stmt->bind_param("ss", $razorpay_payment_id, $razorpay_order_id);
                $stmt->execute();

                // 3. Clear Cart
                if(isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");
                }

                // Send Success Email
                if($user_email) {
                    $subject = "Order Confirmation - Kashmiri Stitch";
                    $message = "<h1>Order Placed Successfully!</h1><p>Dear $user_name,</p><p>Thank you for your order. Your payment (ID: $razorpay_payment_id) was successful.</p><p>We will process your order shortly.</p>";
                    sendEmail($user_email, $user_name, $subject, $message);
                }

            } else {
                $status = $payment_data['status'] ?? 'unknown';
                $error = "Payment Status on Server is: " . $status;

                 // Send Failure Email
                 if($user_email) {
                    $subject = "Payment Failed - Kashmiri Stitch";
                    $message = "<h1>Payment Failed</h1><p>Dear $user_name,</p><p>Your payment for order ID $razorpay_order_id was successful on gateway but status is $status. Please contact support.</p>";
                    sendEmail($user_email, $user_name, $subject, $message);
                }
            }
        }
        
    } else {
        $error = "Invalid Signature";
        // Send Failure Email
        if($user_email) {
            $subject = "Payment Verification Failed - Kashmiri Stitch";
            $message = "<h1>Payment Verification Failed</h1><p>Dear $user_name,</p><p>The payment signature for order ID $razorpay_order_id could not be verified. Please contact support if money was deducted.</p>";
            sendEmail($user_email, $user_name, $subject, $message);
        }
    }
}

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Payment Successful']);
} else {
    echo json_encode(['status' => 'failure', 'message' => $error]);
}
?>
