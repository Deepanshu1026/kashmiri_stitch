<?php
session_start();
header('Content-Type: application/json');

include 'config/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_token = $data['token'] ?? '';

if (empty($id_token)) {
    echo json_encode(['success' => false, 'error' => 'No token provided']);
    exit;
}

// Verify the token with Google
$url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for XAMPP
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    echo json_encode([
        'success' => false, 
        'error' => 'Invalid token validation response', 
        'details' => json_decode($response, true)
    ]);
    exit;
}

$google_user = json_decode($response, true);
$email = $google_user['email'];
$email_verified = $google_user['email_verified'];

if (!$email_verified) {
    echo json_encode(['success' => false, 'error' => 'Email not verified']);
    exit;
}

// User details
$firstname = $data['firstname'] ?? $google_user['given_name'] ?? 'Google';
$lastname = $data['lastname'] ?? $google_user['family_name'] ?? 'User';
$picture = $data['picture'] ?? $google_user['picture'] ?? '';
$google_sub = $google_user['sub']; // Unique Google ID

// Ensure profile_image column exists
$checkCol = $conn->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
if ($checkCol->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL");
}

// Check if user exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists - Log them in
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['firstname'];
    $_SESSION['profile_image'] = $picture ?: ($row['profile_image'] ?? '');
    
    // Update profile image if new one provided
    if (!empty($picture)) {
        $updateStmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $updateStmt->bind_param("si", $picture, $row['id']);
        $updateStmt->execute();
    }
    
    echo json_encode(['success' => true]);
} else {
    // New User - Create account
    // Generate a random password since they are using Google Auth
    $random_password = bin2hex(random_bytes(8));
    $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
    $phone = ''; 

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, phone, profile_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $hashed_password, $phone, $picture);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $firstname;
        $_SESSION['profile_image'] = $picture;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
    }
}
?>
