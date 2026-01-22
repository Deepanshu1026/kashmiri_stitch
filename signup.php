
<?php
session_start();
include 'config/db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone-number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);
        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
             $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $phone, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Log In</a>";
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<?php include 'header.php'; ?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Sign Up</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.html"><i class="flaticon-home"></i>Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Sign Up</span>
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
                            <form action="signup.php" method="POST" class="ul-contact-form">
                                <?php if($error) { echo '<div class="alert alert-danger">'.$error.'</div>'; } ?>
                                <?php if($success) { echo '<div class="alert alert-success">'.$success.'</div>'; } ?>
                                <div class="row">
                                    <!-- firstname -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="text" name="firstname" id="firstname" placeholder="First Name" required>
                                        </div>
                                    </div>

                                    <!-- lastname -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="text" name="lastname" id="lastname" placeholder="Last Name" required>
                                        </div>
                                    </div>

                                    <!-- phone -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="tel" name="phone-number" id="phone-number" placeholder="Phone Number" required>
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="email" name="email" id="email" placeholder="Enter Email Address" required>
                                            <span class="field-icon"><i class="flaticon-email"></i></span>
                                        </div>
                                    </div>

                                    <!-- password -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="password" name="password" id="password" placeholder="Enter Password" required>
                                            <span class="field-icon"><i class="flaticon-lock"></i></span>
                                        </div>
                                    </div>

                                    <!-- CONFIRM PASSWORD -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
                                            <span class="field-icon"><i class="flaticon-lock"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- submit btn -->
                                <button type="submit">Sign Up</button>
                                
                                <div class="text-center mt-3">
                                    <span class="d-block mb-2">Or</span>
                                    <button type="button" id="googleLoginBtn" class="btn btn-light border w-100 d-flex align-items-center justify-content-center" style="gap: 10px; background: #fff; color: #757575;">
                                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google">
                                        Sign up with Google
                                    </button>
                                </div>

                            </form>

                            <p class="text-center mt-4 mb-0">Already have an account? <a href="login.php">Log In</a></p>
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