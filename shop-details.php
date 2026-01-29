


    <?php
    include 'config/db_connect.php';

    $product = null;
    $cart_qty = 0;
    if(isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $sql = "SELECT * FROM products WHERE id='$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $page_title = $product['title']; // Set page title
        }

        // Check cart quantity
        if(isset($_SESSION['user_id'])){
            $uid = $_SESSION['user_id'];
            $c_sql = "SELECT quantity FROM cart WHERE user_id='$uid' AND product_id='$id'";
            $c_res = $conn->query($c_sql);
            if($c_res->num_rows > 0){
                $cart_qty = $c_res->fetch_assoc()['quantity'];
            }
        }
    }
    
    include 'header.php'; // Include header AFTER setting title

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
                    <a href="index.html"><i class="flaticon-home"></i> Home</a>
                    <!-- <div> -->
                    <i class="flaticon-arrow-point-to-right"></i>
                    <a href="shop.html">Shop</a>
                    <!-- </div>
                    <div> -->
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Shop Details</span>
                    <!-- </div> -->
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
                                        <!-- single img -->
                                        <div class="swiper-slide"><img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>"></div>
                                        <!-- single img (duplicate for slider demo) -->
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
                                        <i class="flaticon-star"></i>
                                        <i class="flaticon-star"></i>
                                        <i class="flaticon-star"></i>
                                        <i class="flaticon-star"></i>
                                        <i class="flaticon-star"></i>
                                    </span>
                                    <span class="review-number">(0 Customer Reviews)</span>
                                </div>

                                <!-- price -->
                                <span class="ul-product-details-price">₹<?php echo $product['price']; ?></span>

                                <!-- product title -->
                                <h3 class="ul-product-details-title"><?php echo htmlspecialchars($product['title']); ?></h3>

                                <!-- product description -->
                                <p class="ul-product-details-descr"><?php echo htmlspecialchars($product['description']); ?></p>

                                <!-- product options -->
                                <div class="ul-product-details-options">
                                    <div class="ul-product-details-option ul-product-details-sizes">
                                        <span class="title">Size</span>

                                        <form action="#" class="variants">
                                            <label for="ul-product-details-size-1">
                                                <input type="radio" name="product-size" id="ul-product-details-size-1" checked hidden>
                                                <span class="size-btn">S</span>
                                            </label>

                                            <label for="ul-product-details-size-2">
                                                <input type="radio" name="product-size" id="ul-product-details-size-2" hidden>
                                                <span class="size-btn">M</span>
                                            </label>

                                            <label for="ul-product-details-size-3">
                                                <input type="radio" name="product-size" id="ul-product-details-size-3" hidden>
                                                <span class="size-btn">L</span>
                                            </label>

                                            <label for="ul-product-details-size-4">
                                                <input type="radio" name="product-size" id="ul-product-details-size-4" hidden>
                                                <span class="size-btn">XL</span>
                                            </label>

                                            <label for="ul-product-details-size-5">
                                                <input type="radio" name="product-size" id="ul-product-details-size-5" hidden>
                                                <span class="size-btn">XXL</span>
                                            </label>
                                        </form>
                                    </div>

                                    <div class="ul-product-details-option ul-product-details-colors">
                                        <span class="title">Color</span>
                                        <form action="#" class="variants">
                                            <label for="ul-product-details-color-1">
                                                <input type="radio" name="product-color" id="ul-product-details-color-1" checked hidden>
                                                <span class="color-btn green"></span>
                                            </label>

                                            <label for="ul-product-details-color-2">
                                                <input type="radio" name="product-color" id="ul-product-details-color-2" hidden>
                                                <span class="color-btn blue"></span>
                                            </label>

                                            <label for="ul-product-details-color-3">
                                                <input type="radio" name="product-color" id="ul-product-details-color-3" hidden>
                                                <span class="color-btn brown"></span>
                                            </label>

                                            <label for="ul-product-details-color-4">
                                                <input type="radio" name="product-color" id="ul-product-details-color-4" hidden>
                                                <span class="color-btn red"></span>
                                            </label>
                                        </form>
                                    </div>
                                </div>

                                <!-- product quantity -->
                                <div class="ul-product-details-option ul-product-details-quantity">
                                    <span class="title">Quantity</span>
                                    <form action="#" class="ul-product-quantity-wrapper">
                                        <input type="number" name="product-quantity" id="ul-product-details-quantity" class="ul-product-quantity" value="1" min="1" readonly>
                                        <div class="btns">
                                            <button type="button" class="quantityIncreaseButton"><i class="flaticon-plus"></i></button>
                                            <button type="button" class="quantityDecreaseButton"><i class="flaticon-minus-sign"></i></button>
                                        </div>
                                    </form>
                                    <?php if($cart_qty > 0): ?>
                                        <div style="margin-top: 5px; font-size: 13px; color: #DC2626;">
                                            <i class="flaticon-shopping-bag"></i> <?php echo $cart_qty; ?> already in cart
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- product actions -->
                                <div class="ul-product-details-actions">
                                    <div class="left">
                                        <button class="add-to-cart">Add to Cart <span class="icon"><i class="flaticon-cart"></i></span></button>
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

                <div class="ul-product-details-bottom">
                    <!-- description -->
                    <div class="ul-product-details-long-descr-wrapper">
                        <h3 class="ul-product-details-inner-title">Item Description</h3>
                        <p>Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit.
                            <br>
                            <br>
                            Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis
                        </p>
                    </div>

                    <!-- reviews -->
                    <div class="ul-product-details-reviews">
                        <h3 class="ul-product-details-inner-title">02 Reviews</h3>

                        <!-- single review -->
                        <div class="ul-product-details-review">
                            <!-- reviewer image -->
                            <div class="ul-product-details-review-reviewer-img">
                                <img src="https://ui-avatars.com/api/?name=Temptics+Pro&background=random&color=fff" alt="Reviewer Image">
                            </div>

                            <div class="ul-product-details-review-txt">
                                <div class="header">
                                    <div class="left">
                                        <h4 class="reviewer-name">Temptics Pro</h4>
                                        <h5 class="review-date">March 20, 2023 at 2:37 pm</h5>
                                    </div>

                                    <div class="right">
                                        <div class="rating">
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star-3"></i>
                                        </div>
                                    </div>
                                </div>

                                <p>Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla</p>

                                <button class="ul-product-details-review-reply-btn">Reply</button>
                            </div>
                        </div>

                        <!-- single review -->
                        <div class="ul-product-details-review">
                            <!-- reviewer image -->
                            <div class="ul-product-details-review-reviewer-img">
                                <img src="https://ui-avatars.com/api/?name=Temptics+Pro&background=random&color=fff" alt="Reviewer Image">
                            </div>

                            <div class="ul-product-details-review-txt">
                                <div class="header">
                                    <div class="left">
                                        <h4 class="reviewer-name">Temptics Pro</h4>
                                        <h5 class="review-date">March 20, 2023 at 2:37 pm</h5>
                                    </div>

                                    <div class="right">
                                        <div class="rating">
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star"></i>
                                            <i class="flaticon-star-3"></i>
                                        </div>
                                    </div>
                                </div>

                                <p>Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla</p>

                                <button class="ul-product-details-review-reply-btn">Reply</button>
                            </div>
                        </div>
                    </div>

                    <!-- review form -->
                    <div class="ul-product-details-review-form-wrapper">
                        <h3 class="ul-product-details-inner-title">Write A Review</h3>
                        <span class="note">Your email address will not be published.</span>

                        <form class="ul-product-details-review-form">
                            <div class="form-group rating-field-wrapper">
                                <span class="title">Rate this product? *</span>
                                <div class="rating-field">
                                    <button type="button"><i class="flaticon-star-3"></i></button>
                                    <button type="button"><i class="flaticon-star-3"></i></button>
                                    <button type="button"><i class="flaticon-star-3"></i></button>
                                    <button type="button"><i class="flaticon-star-3"></i></button>
                                    <button type="button"><i class="flaticon-star-3"></i></button>
                                </div>
                            </div>

                            <div class="row row-cols-2 row-cols-xxs-1 ul-bs-row">
                                <div class="form-group">
                                    <input type="text" name="review-name" id="review-name" placeholder="Your Name">
                                </div>

                                <div class="form-group">
                                    <input type="email" name="review-email" id="review-email" placeholder="Your Email">
                                </div>

                                <div class="form-group col-12">
                                    <textarea name="review-message" id="review-message" placeholder="Your Review"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit">Post Review <span><i class="flaticon-up-right-arrow"></i></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT SECTION END -->
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartBtn = document.querySelector('.add-to-cart');
        if(addToCartBtn) {
            addToCartBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const qty = document.getElementById('ul-product-details-quantity').value;
                
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
                
                form.appendChild(pidInput);
                form.appendChild(qtyInput);
                document.body.appendChild(form);
                form.submit();
            });
        }
    });
    </script>
      <?php include 'footer.php'; ?>