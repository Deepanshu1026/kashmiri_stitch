

<?php
session_start();
include 'config/db_connect.php';

// Store redirect URL from Referer if available and safe
if (!isset($_SESSION['redirect_url']) && isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
    $parsed_url = parse_url($referer);
    
    // Debug logging
    file_put_contents('debug_log.txt', "Referer: " . $referer . "\nHost: " . (isset($parsed_url['host']) ? $parsed_url['host'] : 'unset') . "\nServer Host: " . $_SERVER['HTTP_HOST'] . "\n", FILE_APPEND);

    // Only redirect if it's the same host and not login/signup page
    // Relaxed check: just check if host is set (for localhost usually fine) OR matches
    if (isset($parsed_url['host']) && ($parsed_url['host'] === $_SERVER['HTTP_HOST'] || $_SERVER['HTTP_HOST'] === 'localhost')) {
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $page = basename($path);
        
        if ($page !== 'login.php' && $page !== 'signup.php') {
             $_SESSION['redirect_url'] = $referer;
             file_put_contents('debug_log.txt', "Set Redirect: " . $referer . "\n", FILE_APPEND);
        }
    }
}


// Redirect if already logged in
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : "index.php";
    unset($_SESSION['redirect_url']);
    header("Location: " . $redirect_url);
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['firstname'];

            
            $redirect_location = "index.php";
            if(isset($_SESSION['redirect_url'])) {
                $redirect_location = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
            }
            header("Location: " . $redirect_location);
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with that email!";
    }
}
?>
    <?php include 'header.php'; ?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Log In</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.html"><i class="flaticon-home"></i> Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Log In</span>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB SECTION END -->



        <div class="ul-container">
            <div class="ul-login">
                <div class="ul-inner-page-container">
                    <div class="row justify-content-evenly align-items-center flex-column-reverse flex-md-row">
                        <div class="col-md-5">
                            <div class="ul-login-img text-center">
                                <img src="assets/img/login-img.png" alt="Login Image">
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-7">
                            <form action="login.php" method="POST" class="ul-contact-form">
                                <?php if($error) { echo '<div class="alert alert-danger">'.$error.'</div>'; } ?>
                                <div class="row">
                                    <!-- email -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="email" name="email" id="email"
                                                placeholder="Enter Email Address" required>
                                        </div>
                                    </div>

                                    <!-- password -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="password" name="password" id="password"
                                                placeholder="Enter Password" required>
                                        </div>
                                    </div>
                                </div>
                            <!-- submit btn -->
                                <button type="submit">Log In</button>
                                
                                <div class="text-center mt-3">
                                    <span class="d-block mb-2">Or</span>
                                    <button type="button" id="googleLoginBtn" class="btn btn-light border w-100 d-flex align-items-center justify-content-center" style="gap: 10px; background: #fff; color: #757575;">
                                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google">
                                        Sign in with Google
                                    </button>
                                </div>

                            </form>

                            <p class="text-center mt-4 mb-0">Already have an account? <a href="signup.php">Sign Up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <script src="assets/js/google-auth.js"></script>

      <?php include 'footer.php'; ?>