


    <?php
    include 'config/db_connect.php';

    $product = null;
    $cart_qty = 0;
    $sizes_arr = [];
    $colors_arr = [];

    if(isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $sql = "SELECT * FROM products WHERE id='$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $page_title = $product['title'];

            // Parse sizes and colors
            if (!empty($product['available_sizes'])) {
                $sizes_arr = array_map('trim', explode(',', $product['available_sizes']));
            }
            if (!empty($product['available_colors'])) {
                $colors_arr = array_map('trim', explode(',', $product['available_colors']));
            }
        }

        // Fetch all cart items for this product to support dynamic switching
        $cart_variants = [];
        $cart_qty_default = 0; // For the initially selected variant
        
        if(isset($_SESSION['user_id'])){
            $uid = $_SESSION['user_id'];
            $cv_sql = "SELECT size, color, quantity FROM cart WHERE user_id='$uid' AND product_id='$id'";
            $cv_res = $conn->query($cv_sql);
            if($cv_res){
                while($row = $cv_res->fetch_assoc()){
                    // Use a standardized key for JS lookup: "Size_Color"
                    // Handle empty/null values as empty string
                    $s = trim($row['size'] ?? '');
                    $c = trim($row['color'] ?? '');
                    $key = $s . '_' . $c;
                    $cart_variants[$key] = $row['quantity'];
                }
            }

            // Determine default quantity (first available options)
            $default_size = $sizes_arr[0] ?? '';
            $default_color = $colors_arr[0] ?? '';
            $default_key = $default_size . '_' . $default_color;
            
            if(isset($cart_variants[$default_key])){
                $cart_qty = $cart_variants[$default_key];
            }
        }
    }

    // Fetch Reviews
    $reviews_sql = "SELECT r.*, u.firstname, u.lastname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = '$id' ORDER BY r.created_at DESC";
    $reviews_res = $conn->query($reviews_sql);
    $total_reviews = $reviews_res->num_rows;
    $avg_rating = 0;
    if($total_reviews > 0){
        $sum_rating = 0;
        while($row = $reviews_res->fetch_assoc()){
            $sum_rating += $row['rating'];
        }
        $avg_rating = round($sum_rating / $total_reviews);
        $reviews_res->data_seek(0);
    }
    
    include 'header.php'; 

    if(!$product) {
        echo "<div class='ul-container' style='padding:50px;'><h3 class='text-center'>Product Not Found</h3></div>";
        include 'footer.php';
        exit();
    }
    ?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Shop Details</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.php"><i class="flaticon-home"></i> Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <a href="shop.php">Shop</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page"><?php echo htmlspecialchars($product['title']); ?></span>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB SECTION END -->

        <!-- MAIN CONTENT SECTION START -->
        <div class="ul-inner-page-container">
            <div class="ul-product-details">
                <div class="ul-product-details-top">
                    <div class="row ul-bs-row row-cols-lg-2 row-cols-1 align-items-center">
                        <!-- img -->
                        <div class="col">
                            <div class="ul-product-details-img">
                                <div class="ul-product-details-img-slider swiper">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide"><img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>"></div>
                                        <div class="swiper-slide"><img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>"></div>
                                    </div>

                                    <div class="ul-product-details-img-slider-nav" id="ul-product-details-img-slider-nav">
                                        <button class="prev"><i class="flaticon-left-arrow"></i></button>
                                        <button class="next"><i class="flaticon-arrow-point-to-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- txt -->
                        <div class="col">
                            <div class="ul-product-details-txt">
                                <!-- product rating -->
                                <div class="ul-product-details-rating">
                                    <span class="rating">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <i class="flaticon-star<?php echo ($i <= $avg_rating) ? '' : '-3'; // Assuming '-3' is empty star style or just different class usually ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="review-number">(<?php echo $total_reviews; ?> Customer Reviews)</span>
                                </div>

                                <!-- price -->
                                <span class="ul-product-details-price">₹<?php echo $product['price']; ?></span>

                                <!-- product title -->
                                <h3 class="ul-product-details-title"><?php echo htmlspecialchars($product['title']); ?></h3>

                                <!-- product description -->
                                <p class="ul-product-details-descr"><?php echo htmlspecialchars($product['description']); ?></p>

                                <!-- product options -->
                                <div class="ul-product-details-options">
                                    <?php if(!empty($sizes_arr)): ?>
                                    <div class="ul-product-details-option ul-product-details-sizes">
                                        <span class="title">Size</span>
                                        <div class="variants">
                                            <?php foreach($sizes_arr as $idx => $size): $size = trim($size); ?>
                                            <label for="ul-product-details-size-<?php echo $idx; ?>">
                                                <input type="radio" name="product-size" id="ul-product-details-size-<?php echo $idx; ?>" value="<?php echo htmlspecialchars($size); ?>" <?php echo ($idx === 0) ? 'checked' : ''; ?> hidden>
                                                <span class="size-btn"><?php echo htmlspecialchars($size); ?></span>
                                            </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(!empty($colors_arr)): ?>
                                    <div class="ul-product-details-option ul-product-details-colors">
                                        <span class="title">Color</span>
                                        <div class="variants">
                                            <?php foreach($colors_arr as $idx => $color): $color = trim($color); ?>
                                            <label for="ul-product-details-color-<?php echo $idx; ?>">
                                                <input type="radio" name="product-color" id="ul-product-details-color-<?php echo $idx; ?>" value="<?php echo htmlspecialchars($color); ?>" <?php echo ($idx === 0) ? 'checked' : ''; ?> hidden>
                                                <span class="color-btn" style="background-color: <?php echo htmlspecialchars($color); ?>;"></span>
                                            </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- product quantity -->
                                <div class="ul-product-details-option ul-product-details-quantity">
                                    <span class="title">Quantity</span>
                                    <form action="#" class="ul-product-quantity-wrapper">
                                        <input type="number" name="product-quantity" id="ul-product-details-quantity" class="ul-product-quantity" value="<?php echo ($cart_qty > 0) ? $cart_qty : 1; ?>" min="1" readonly>
                                        <div class="btns">
                                            <button type="button" class="quantityIncreaseButton"><i class="flaticon-plus"></i></button>
                                            <button type="button" class="quantityDecreaseButton"><i class="flaticon-minus-sign"></i></button>
                                        </div>
                                    </form>
                                    <div id="cart-quantity-msg" style="margin-top: 5px; font-size: 13px; color: #DC2626; display: <?php echo ($cart_qty > 0) ? 'block' : 'none'; ?>;">
                                        <i class="flaticon-shopping-bag"></i> <span id="cart-qty-val"><?php echo $cart_qty; ?></span> already in cart
                                    </div>
                                </div>

                                <!-- product actions -->
                                <div class="ul-product-details-actions">
                                    <div class="left">
                                        <button class="add-to-cart">
                                            Add to Cart 
                                            <span id="btn-cart-count" style="font-weight: bold; margin-left: 5px; display: <?php echo ($cart_qty > 0) ? 'inline' : 'none'; ?>;">
                                                (<?php echo $cart_qty; ?>)
                                            </span>
                                            <span class="icon"><i class="flaticon-cart"></i></span>
                                        </button>
                                        <button class="add-to-wishlist" data-pid="<?php echo $product['id']; ?>"><span class="icon"><i class="flaticon-heart"></i></span> Add to wishlist</button>
                                    </div>
                                    <div class="share-options">
                                        <button><i class="flaticon-facebook-app-symbol"></i></button>
                                        <button><i class="flaticon-twitter"></i></button>
                                        <button><i class="flaticon-linkedin-big-logo"></i></button>
                                        <a href="#"><i class="flaticon-youtube"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT SECTION END -->
    </main>
    
    <!-- Pass cart variants to JS -->
    <script>
        var cartVariants = <?php echo json_encode($cart_variants); ?>;
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartBtn = document.querySelector('.add-to-cart');
        if(addToCartBtn) {
            addToCartBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const qty = document.getElementById('ul-product-details-quantity').value;
                
                // Get Selected Size
                let selectedSize = '';
                const sizeInput = document.querySelector('input[name="product-size"]:checked');
                if(sizeInput) selectedSize = sizeInput.value;

                // Get Selected Color
                let selectedColor = '';
                const colorInput = document.querySelector('input[name="product-color"]:checked');
                if(colorInput) selectedColor = colorInput.value;

                // Simple validation if options are present but not selected (though defaults are checked in PHP)
                const hasSizes = document.querySelector('.ul-product-details-sizes') !== null;
                const hasColors = document.querySelector('.ul-product-details-colors') !== null;

                if(hasSizes && !selectedSize) {
                    alert('Please select a size');
                    return;
                }
                if(hasColors && !selectedColor) {
                    alert('Please select a color');
                    return;
                }

                // Create a form programmatically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'add_to_cart.php';
                
                const pidInput = document.createElement('input');
                pidInput.type = 'hidden';
                pidInput.name = 'product_id';
                pidInput.value = '<?php echo $product['id']; ?>';
                
                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'quantity';
                qtyInput.value = qty;
                
                const sizeInputH = document.createElement('input');
                sizeInputH.type = 'hidden';
                sizeInputH.name = 'size';
                sizeInputH.value = selectedSize;

                const colorInputH = document.createElement('input');
                colorInputH.type = 'hidden';
                colorInputH.name = 'color';
                colorInputH.value = selectedColor;
                
                form.appendChild(pidInput);
                form.appendChild(qtyInput);
                form.appendChild(sizeInputH);
                form.appendChild(colorInputH);
                
                document.body.appendChild(form);
                form.submit();
            });
        }

        // Star Rating Interaction
        const starBtns = document.querySelectorAll('.star-rate-btn');
        const ratingInput = document.getElementById('review_rating_input');
        
        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                ratingInput.value = val;
                
                // Update visuals
                starBtns.forEach(b => {
                    if(b.getAttribute('data-value') <= val) {
                        b.innerHTML = '<i class="flaticon-star"></i>';
                    } else {
                        b.innerHTML = '<i class="flaticon-star-3"></i>';
                    }
                });
            });
        });

        // Review Form Submission
        const reviewForm = document.getElementById('reviewForm');
        if(reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('submit_review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        if(data.message.toLowerCase().includes('login')) {
                             if(confirm(data.message)) {
                                 window.location.href = 'login.php';
                             }
                        } else {
                            alert(data.message);
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        }

        // Handle Dynamic Quantity Display based on Variant Selection
        const sizeRadios = document.querySelectorAll('input[name="product-size"]');
        const colorRadios = document.querySelectorAll('input[name="product-color"]');
        const qtyDisplay = document.getElementById('ul-product-details-quantity');
        const cartMsgDiv = document.getElementById('cart-quantity-msg');
        const cartQtyVal = document.getElementById('cart-qty-val');
        const btnCartCount = document.getElementById('btn-cart-count');

        function updateQuantityDisplay() {
             let s = '';
             let c = '';
             const checkedSize = document.querySelector('input[name="product-size"]:checked');
             if(checkedSize) s = checkedSize.value;
             
             const checkedColor = document.querySelector('input[name="product-color"]:checked');
             if(checkedColor) c = checkedColor.value;

             // Construct Key (Trimmed to match PHP)
             const key = s.trim() + '_' + c.trim();
             
             if(cartVariants && cartVariants[key]) {
                 const qty = cartVariants[key]; 
                 qtyDisplay.value = qty; 
                 if(cartQtyVal) cartQtyVal.innerText = qty;
                 if(cartMsgDiv) cartMsgDiv.style.display = 'block';
                 
                 // Update Button Count
                 if(btnCartCount) {
                     btnCartCount.innerText = '(' + qty + ')';
                     btnCartCount.style.display = 'inline';
                 }
             } else {
                 qtyDisplay.value = 1;
                 if(cartMsgDiv) cartMsgDiv.style.display = 'none';
                 
                 // Hide Button Count
                 if(btnCartCount) {
                     btnCartCount.innerText = '';
                     btnCartCount.style.display = 'none';
                 }
             }
        }

        sizeRadios.forEach(r => r.addEventListener('change', updateQuantityDisplay));
        colorRadios.forEach(r => r.addEventListener('change', updateQuantityDisplay));
    });
    </script>
      <?php include 'footer.php'; ?>