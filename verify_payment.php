<?php
ob_start();
session_start();
header('Content-Type: application/json');

ob_clean(); // Clean any previous output

include 'config/db_connect.php';
include 'config/razorpay_config.php';

$success = false;
$error = "Payment Failed";

if (isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id']) && isset($_POST['razorpay_signature'])) {
    
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];

    // 1. Verify Signature
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, RAZORPAY_KEY_SECRET);

    if ($generated_signature == $razorpay_signature) {
        
        // 1.1 Strong Verification: Call Razorpay API to check status
        // This ensures the server HAS internet and the payment is genuinely 'captured' on Razorpay server
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
            } else {
                $status = $payment_data['status'] ?? 'unknown';
                $error = "Payment Status on Server is: " . $status;
            }
        }
        
    } else {
        $error = "Invalid Signature";
    }
}

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Payment Successful']);
} else {
    echo json_encode(['status' => 'failure', 'message' => $error]);
}
?>
