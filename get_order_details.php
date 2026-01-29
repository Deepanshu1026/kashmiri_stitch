<?php
include 'config/db_connect.php';

if(isset($_GET['order_id'])) {
    $order_id = $conn->real_escape_string($_GET['order_id']);
    
    // Fetch Order Info
    $order_sql = "SELECT * FROM orders WHERE id = '$order_id'";
    $order_res = $conn->query($order_sql);
    
    if($order_res && $order_res->num_rows > 0) {
        $order = $order_res->fetch_assoc();
        
        // Fetch Items
        $items_sql = "SELECT oi.*, p.title, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = '$order_id'";
        $items_res = $conn->query($items_sql);
        
        $items = [];
        if($items_res && $items_res->num_rows > 0) {
            while($item = $items_res->fetch_assoc()) {
                $items[] = $item;
            }
        }
        
        echo json_encode(['status' => 'success', 'order' => $order, 'items' => $items]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>
