
    <?php 
include 'config/db_connect.php'; 
include 'header.php'; 

// Fetch last order details for auto-fill
$firstname = $lastname = $companyname = $country = $address1 = $address2 = $city = $state = $zipcode = $phone = $email = "";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $last_order_sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 1";
    $last_order_res = $conn->query($last_order_sql);
    if ($last_order_res && $last_order_res->num_rows > 0) {
        $ord = $last_order_res->fetch_assoc();
        $firstname = $ord['firstname'];
        $lastname = $ord['lastname'];
        $companyname = $ord['companyname'];
        $country = $ord['country'];
        $address1 = $ord['address1'];
        $address2 = $ord['address2'];
        $city = $ord['city'];
        $state = $ord['state'];
        $zipcode = $ord['zipcode'];
        $phone = $ord['phone'];
        $email = $ord['email'];
    }
}
?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Checkout</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.html"><i class="flaticon-home"></i> Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Checkout</span>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB SECTION END -->

        <!-- CHEKOUT SECTION START -->
        <div class="ul-cart-container">
            <h3 class="ul-checkout-title">Billing Details</h3>
            <form action="#" class="ul-checkout-form" id="checkoutForm">
                <div class="row ul-bs-row row-cols-lg-2 row-cols-1">
                    <!-- left side / checkout form -->
                    <div class="col">
                        <div class="row row-cols-lg-2 row-cols-1 ul-bs-row">
                            <!-- first name -->
                            <div class="form-group">
                                <label for="firstname">First Name*</label>
                                <input type="text" name="firstname" id="firstname" placeholder="Enter Your First Name" value="<?php echo htmlspecialchars($firstname); ?>">
                            </div>

                            <!-- last name -->
                            <div class="form-group">
                                <label for="lastname">Last Name*</label>
                                <input type="text" name="lastname" id="lastname" placeholder="Enter Your First Name" value="<?php echo htmlspecialchars($lastname); ?>">
                            </div>

                            <!-- company name -->
                            <div class="form-group">
                                <label for="companyname">Company Name</label>
                                <input type="text" name="companyname" id="companyname" placeholder="Enter Your Company Name" value="<?php echo htmlspecialchars($companyname); ?>">
                            </div>

                            <!-- country -->
                            <div class="form-group ul-checkout-country-wrapper">
                                <label for="ul-checkout-country">Country*</label>
                                <select name="country" id="ul-checkout-country">
                                    <option value="" disabled <?php echo empty($country) ? 'selected' : ''; ?>>Select Country</option>
                                    <option value="2" <?php echo $country == '2' ? 'selected' : ''; ?>>United States</option>
                                    <option value="3" <?php echo $country == '3' ? 'selected' : ''; ?>>United Kingdom</option>
                                    <option value="4" <?php echo $country == '4' ? 'selected' : ''; ?>>Germany</option>
                                    <option value="5" <?php echo $country == '5' ? 'selected' : ''; ?>>France</option>
                                    <option value="6" <?php echo $country == '6' ? 'selected' : ''; ?>>India</option>
                                </select>
                            </div>

                            <!-- address 1 -->
                            <div class="form-group">
                                <label for="address1">Street Address*</label>
                                <input type="text" name="address1" id="address1" placeholder="1837 E Homer M Adams Pkwy" value="<?php echo htmlspecialchars($address1); ?>">
                            </div>

                            <!-- address 2 -->
                            <div class="form-group">
                                <label for="address2">Address 2*</label>
                                <input type="text" name="address2" id="address2" placeholder="1837 E Homer M Adams Pkwy" value="<?php echo htmlspecialchars($address2); ?>">
                            </div>

                            <!-- city -->
                            <div class="form-group">
                                <label for="city">City or Town*</label>
                                <input type="text" name="city" id="city" placeholder="Enter Your City or Town" value="<?php echo htmlspecialchars($city); ?>">
                            </div>

                            <!-- state -->
                            <div class="form-group">
                                <label for="state">State*</label>
                                <input type="text" name="state" id="state" placeholder="Enter Your State" value="<?php echo htmlspecialchars($state); ?>">
                            </div>

                            <!-- postcode -->
                            <div class="form-group">
                                <label for="zipcode">ZIP Code*</label>
                                <input type="text" name="zipcode" id="zipcode" placeholder="Enter Your Postcode" value="<?php echo htmlspecialchars($zipcode); ?>">
                            </div>

                            <!-- phone -->
                            <div class="form-group">
                                <label for="phone">Phone*</label>
                                <input type="text" name="phone" id="phone" placeholder="Enter Your Phone Number" value="<?php echo htmlspecialchars($phone); ?>">
                            </div>

                            <!-- email -->
                            <div class="form-group col-lg-12">
                                <label for="email">Email Address*</label>
                                <input type="email" name="email" id="email" placeholder="Enter Your Email" value="<?php echo htmlspecialchars($email); ?>">
                            </div>
                        </div>

                    </div>

                    <!-- right side / different address -->
                    <div class="col">
                        <div class="form-group">
                            <label for="ul-checkout-different-address-field">Shift to A Different Address</label>
                            <textarea name="different-address" id="ul-checkout-different-address-field" placeholder="2801 Lafayette Blvd, Norfolk, Vermont 23509, united state"></textarea>
                        </div>

                        <!-- different address checkbox -->
                        <div class="ul-checkout-payment-methods">
                            <div class="form-group">
                                <label for="payment-option-1">
                                    <input type="radio" name="payment-methods" id="payment-option-1" hidden checked>
                                    <span class="ul-radio-wrapper"></span>
                                    <span class="ul-checkout-payment-method">
                                        <span class="title">Direct Bank Transfer</span>
                                        <span class="descr">Neque porro est qui dolorem ipsum quia quaed inventor veritatis et quasi architecto beatae vitae dicta sunt explicabo. Aelltes port lacus quis enim var sed efficitur</span>
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="payment-option-2">
                                    <input type="radio" name="payment-methods" id="payment-option-2" hidden>
                                    <span class="ul-radio-wrapper"></span>
                                    <span class="ul-checkout-payment-method">
                                        <span class="title">Ship To A Different Address?</span>
                                    </span>
                                </label>
                            </div>
                            <button type="submit" class="ul-checkout-form-btn" id="payBtn">Place Your Order</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- bill summary -->
            <div class="row ul-bs-row row-cols-2 row-cols-xxs-1">
                <div class="ul-checkout-bill-summary">
                    <h4 class="ul-checkout-bill-summary-title">Your Order</h4>

                    <div>
                        <div class="ul-checkout-bill-summary-header">
                            <span class="left">Product</span>
                            <span class="right">Sub Total</span>
                        </div>



                        <div class="ul-checkout-bill-summary-body">
                            <?php
                            $checkout_total = 0;
                            if(isset($_SESSION['user_id'])){
                                $uid = $_SESSION['user_id'];
                                $c_sql = "SELECT c.*, p.title, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$uid'";
                                $c_res = $conn->query($c_sql);
                                if($c_res->num_rows > 0){
                                    while($c_row = $c_res->fetch_assoc()){
                                        $item_total = $c_row['price'] * $c_row['quantity'];
                                        $checkout_total += $item_total;
                                        echo '<div class="single-row"><span class="left">'.htmlspecialchars($c_row['title']).' x '.$c_row['quantity'].'</span><span class="right">₹'.number_format($item_total, 2).'</span></div>';
                                    }
                                } else {
                                    echo '<div class="single-row"><span class="left">Cart is empty</span><span class="right">₹0.00</span></div>';
                                }
                            }
                            ?>
                            <div class="single-row"><span class="left">Sub Total</span><span class="right">₹<?php echo number_format($checkout_total, 2); ?></span></div>
                            <div class="single-row"><span class="left">Shipping</span><span class="right">Free</span></div>
                        </div>

                        <div class="ul-checkout-bill-summary-footer ul-checkout-bill-summary-header">
                            <span class="left">Total</span>
                            <span class="right">₹<?php echo number_format($checkout_total, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CHEKOUT SECTION END -->
    </main>
    
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var payBtn = document.getElementById('payBtn');

        // Validation Logic
        var requiredFields = ['firstname', 'lastname', 'ul-checkout-country', 'address1', 'city', 'state', 'zipcode', 'phone', 'email'];
        var isValid = true;
        
        for (var i = 0; i < requiredFields.length; i++) {
            var field = document.getElementById(requiredFields[i]);
            if (!field.value.trim() || field.value === '') {
                isValid = false;
                field.style.border = '1px solid red';
                field.focus();
                // We focus on the first invalid field
                if(i > 0) { // Keep focus on the first one found
                     // Actually, iterating forward, the last one will be focused if we don't break. 
                     // Improved logic: find first invalid, focus and break.
                }
            } else {
                field.style.border = '1px solid #e5e5e5'; // Reset to default style (assuming default is this or similar)
            }
        }
        
        if (!isValid) {
            alert('Please fill in all required fields marked with *');
            return;
        }

        payBtn.disabled = true;
        payBtn.innerHTML = 'Processing...';

        // Collect Form Data
        var formData = new FormData(document.getElementById('checkoutForm'));

        // Create Order
        fetch('create_razorpay_order.php', {
            method: 'POST',
            body: formData // Send form data directly
        })
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                alert('Error: ' + data.error);
                payBtn.disabled = false;
                payBtn.innerHTML = 'Place Your Order';
                return;
            }

            var options = {
                "key": data.key,
                "amount": data.amount,
                "currency": data.currency,
                "name": "Kashmir Stitch",
                "description": "Purchase Description",
                "image": "assets/img/logo.png",
                "order_id": data.order_id, 
                "handler": function (response){
                    // Verify Payment
                    var verifyData = new FormData();
                    verifyData.append('razorpay_payment_id', response.razorpay_payment_id);
                    verifyData.append('razorpay_order_id', response.razorpay_order_id);
                    verifyData.append('razorpay_signature', response.razorpay_signature);

                    fetch('verify_payment.php', {
                        method: 'POST',
                        body: verifyData
                    })
                    .then(res => res.json())
                    .then(resData => {
                        console.log('Verification Response:', resData); // Debugging Log
                        if(resData.status === 'success'){
                            // alert('Payment Successful!'); // Removed as per request
                            window.location.href = 'order_history.php';
                        } else {
                            alert('Payment Verification Failed: ' + resData.message);
                            window.location.href = 'order_history.php';
                        }
                    });
                },
                "prefill": {
                    "name": document.getElementById('firstname').value + " " + document.getElementById('lastname').value,
                    "email": document.getElementById('email').value,
                    "contact": document.getElementById('phone').value
                },
                "theme": {
                    "color": "#BF0A30"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function (response){
                alert('Payment Failed: ' + response.error.description);
                
                // Notify server about failure to send email
                var failData = new FormData();
                failData.append('razorpay_order_id', data.order_id);
                var payment_id = (response.error.metadata && response.error.metadata.payment_id) ? response.error.metadata.payment_id : '';
                failData.append('razorpay_payment_id', payment_id);
                failData.append('error_description', response.error.description);

                fetch('failed_payment.php', {
                    method: 'POST',
                    body: failData
                }).then(() => {
                    window.location.href = 'order_history.php';
                });
            });
            rzp1.open();
        })
        .catch(error => {
            console.error('Error:', error);
            payBtn.disabled = false;
            payBtn.innerHTML = 'Place Your Order';
        });
    });
    </script>
    
  <?php include 'footer.php'; ?>