<?php
ob_start(); // Start output buffering to prevent unwanted output
session_start();
header('Content-Type: application/json');

// Ensure no previous output
ob_clean();

include 'config/db_connect.php';
include 'config/razorpay_config.php';

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    $user_id = $_SESSION['user_id'];

    // Validate User Exists
    $uChk = $conn->query("SELECT id FROM users WHERE id='$user_id'");
    if($uChk->num_rows == 0){
        session_destroy();
        throw new Exception('User record not found. Please relogin.');
    }

    // 1. Calculate Total Amount from Cart
    $sql = "SELECT c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception('Database Query Failed: ' . $conn->error);
    }

    $total_amount = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_amount += $row['price'] * $row['quantity'];
        }
    }

    if ($total_amount <= 0) {
        throw new Exception('Cart is empty');
    }

    // Razorpay Amount is in paise (1 INR = 100 paise)
    $api_amount = $total_amount * 100;

    // 2. Create Orders Table if not exists - WITH ALL COLUMNS
    // This avoids the need for risky ALTER TABLE loops on every request
    $sql_table = "CREATE TABLE IF NOT EXISTS orders (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        razorpay_order_id VARCHAR(50),
        razorpay_payment_id VARCHAR(50),
        status VARCHAR(20) DEFAULT 'pending',
        amount DECIMAL(10, 2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        firstname VARCHAR(50),
        lastname VARCHAR(50),
        companyname VARCHAR(100),
        country VARCHAR(50),
        address1 VARCHAR(255),
        address2 VARCHAR(255),
        city VARCHAR(50),
        state VARCHAR(50),
        zipcode VARCHAR(20),
        phone VARCHAR(20),
        email VARCHAR(100)
    )";
    if (!$conn->query($sql_table)) {
         throw new Exception('Failed to create orders table: ' . $conn->error);
    }

    // Create Order Items Table
    $sql_items_table = "CREATE TABLE IF NOT EXISTS order_items (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id INT(6) UNSIGNED NOT NULL,
        product_id INT(6) UNSIGNED NOT NULL,
        quantity INT(6) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )";
    if (!$conn->query($sql_items_table)) {
        throw new Exception('Failed to create order_items table: ' . $conn->error);
    }

    // 3. Create Order via Razorpay API using cURL
    $url = "https://api.razorpay.com/v1/orders";

    $data = [
        'amount' => $api_amount,
        'currency' => 'INR',
        'receipt' => 'receipt_' . uniqid(),
        'payment_capture' => 1 // Auto capture
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ":" . RAZORPAY_KEY_SECRET);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for XAMPP SSL issue
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('cURL Error: ' . curl_error($ch));
    }
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status === 200) {
        $razorpay_order = json_decode($response, true);
        if (!$razorpay_order) {
            throw new Exception('Invalid JSON from Razorpay');
        }
        $razorpay_order_id = $razorpay_order['id'];

        // Collect Form Data
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $companyname = $_POST['companyname'] ?? '';
        $country = $_POST['country'] ?? '';
        $address1 = $_POST['address1'] ?? '';
        $address2 = $_POST['address2'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $zipcode = $_POST['zipcode'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';

        // 4. Insert into Local Database
        $stmt = $conn->prepare("INSERT INTO orders (user_id, razorpay_order_id, amount, status, firstname, lastname, companyname, country, address1, address2, city, state, zipcode, phone, email) VALUES (?, ?, ?, 'created', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
             throw new Exception('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("isdsssssssssss", $user_id, $razorpay_order_id, $total_amount, $firstname, $lastname, $companyname, $country, $address1, $address2, $city, $state, $zipcode, $phone, $email);
        
        if($stmt->execute()) {
            $new_order_id = $stmt->insert_id; // Get the auto-generated ID

            // 5. Insert Cart Items into order_items
            // We need to fetch cart items again or reset pointer if we already fetched them. 
            // In step 1 we calculated total, let's fetch details now.
            $cart_sql = "SELECT c.quantity, p.id as p_id, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";
            $cart_res_items = $conn->query($cart_sql);
            
            if ($cart_res_items && $cart_res_items->num_rows > 0) {
                $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                while($c_item = $cart_res_items->fetch_assoc()){
                    $item_stmt->bind_param("iiid", $new_order_id, $c_item['p_id'], $c_item['quantity'], $c_item['price']);
                    $item_stmt->execute();
                }
            }

            echo json_encode([
                'key' => RAZORPAY_KEY_ID,
                'amount' => $api_amount,
                'currency' => 'INR',
                'order_id' => $razorpay_order_id,
                'user_email' => $email,
                'user_contact' => $phone
            ]);
        } else {
            throw new Exception('Database Insert Error: ' . $stmt->error);
        }

    } else {
        throw new Exception('Razorpay API Error: ' . $response);
    }

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
