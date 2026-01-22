<?php
// This file handles webhooks from Razorpay
// URL to configure in Razorpay Dashboard: https://kashmirstitch.com/razorpay_webhook.php
// Events to enable in Dashboard: 
// payment.authorized, payment.captured, payment.failed, refund.processed, order.paid

include 'config/db_connect.php';
include 'config/razorpay_config.php';

$webhook_secret = 'qLrBhFUR786_6Ri'; // Set this in Razorpay Dashboard

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

// Verify Webhook Signature
if (!empty($webhook_secret)) {
    $expected_signature = hash_hmac('sha256', $payload, $webhook_secret);
    if ($signature !== $expected_signature) {
        http_response_code(400);
        exit();
    }
}

$data = json_decode($payload, true);

if (isset($data['event']) && isset($data['payload']['payment']['entity'])) {
    $payment = $data['payload']['payment']['entity'];
    $payment_id = $payment['id'];
    $order_id = $payment['order_id'];
    $event = $data['event'];
    
    $status = '';

    switch ($event) {
        case 'payment.authorized':
            $status = 'authorized';
            break;
            
        case 'payment.captured':
            $status = 'captured';
            break;
            
        case 'payment.failed':
            $status = 'failed';
            break;
            
        case 'refund.processed':
            // Check if fully refunded or partially
            // The payload for refund.processed includes 'refund' entity usually, but payment entity has 'amount_refunded'
            if (isset($payment['amount']) && isset($payment['amount_refunded'])) {
                if ($payment['amount_refunded'] == $payment['amount']) {
                    $status = 'refunded';
                } else {
                    $status = 'partial_refunded';
                }
            } else {
                $status = 'refunded'; // Fallback
            }
            break;
            
        case 'payment.pending':
             $status = 'pending';
             break;

        // Add other cases if needed
        default:
            // For other events, we might not want to update status blindly
            break;
    }

    if (!empty($status)) {
        // Update order status
        // We use order_id to find the record. 
        // Note: For payment.failed, order_id might be present.
        
        $stmt = $conn->prepare("UPDATE orders SET status = ?, razorpay_payment_id = ? WHERE razorpay_order_id = ?");
        $stmt->bind_param("sss", $status, $payment_id, $order_id);
        $stmt->execute();
    }
}

http_response_code(200);
?>
