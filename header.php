<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Kashmiri Stitch' : 'Kashmiri Stitch'; ?></title>

    <!-- libraries CSS -->
    <link rel="stylesheet" href="assets/icon/flaticon_glamer.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/splide/splide.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/vendor/slim-select/slimselect.css">
    <link rel="stylesheet" href="assets/vendor/animate-wow/animate.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <!-- custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .ul-header-cart-icon, .ul-header-wishlist-icon {
            position: relative;
            display: inline-flex !important; /* Ensure it behaves like a container */
            align-items: center;
            justify-content: center;
        }
        .ul-header-cart-count {
            position: absolute;
            top: -5px;
            right: -8px;
            background-color: #BF0A30; /* Theme red color or something prominent */
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }
    </style>
</head>

<body>
    <div class="preloader" id="preloader">
        <div class="loader"></div>
    </div>

    <!-- SIDEBAR SECTION START -->
    <div class="ul-sidebar">
        <!-- header -->
        <div class="ul-sidebar-header">
            <div class="ul-sidebar-header-logo">
                <a href="index.php">
                    <img src="assets/img/logo.png" alt="logo" class="logo">
                </a>
            </div>
            <!-- sidebar closer -->
            <button class="ul-sidebar-closer"><i class="flaticon-close"></i></button>
        </div>

        <div class="ul-sidebar-header-nav-wrapper d-block d-lg-none"></div>

        <div class="ul-sidebar-about d-none d-lg-block">
            <span class="title">About glamer</span>
            <p class="mb-0">Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo,
                scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus.
                Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit
                viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut
                dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec
                dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla
                quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo
                ut lacinia. Phasellus pharetra velit.</p>
        </div>


        <!-- product slider -->
        <div class="ul-sidebar-products-wrapper d-none d-lg-flex">
            <div class="ul-sidebar-products-slider swiper">
                <div class="swiper-wrapper">
                    <!-- product card -->
                    <div class="swiper-slide">
                        <div class="ul-product">
                            <div class="ul-product-heading">
                                <span class="ul-product-price">₹99.00</span>
                                <span class="ul-product-discount-tag">25% Off</span>
                            </div>

                            <div class="ul-product-img">
                                <img src="assets/img/product-img-1.jpg" alt="Product Image">

                                <div class="ul-product-actions">
                                    <button><i class="flaticon-shopping-bag"></i></button>
                                    <a href="#"><i class="flaticon-hide"></i></a>
                                    <button><i class="flaticon-heart"></i></button>
                                </div>
                            </div>

                            <div class="ul-product-txt">
                                <h4 class="ul-product-title"><a href="shop-details.php">Orange Airsuit</a></h4>
                                <h5 class="ul-product-category"><a href="shop.php">Fashion Bag</a></h5>
                            </div>
                        </div>
                    </div>

                    <!-- product card -->
                    <div class="swiper-slide">
                        <div class="ul-product">
                            <div class="ul-product-heading">
                                <span class="ul-product-price">₹99.00</span>
                                <span class="ul-product-discount-tag">25% Off</span>
                            </div>

                            <div class="ul-product-img">
                                <img src="assets/img/product-img-2.jpg" alt="Product Image">

                                <div class="ul-product-actions">
                                    <button><i class="flaticon-shopping-bag"></i></button>
                                    <a href="#"><i class="flaticon-hide"></i></a>
                                    <button><i class="flaticon-heart"></i></button>
                                </div>
                            </div>

                            <div class="ul-product-txt">
                                <h4 class="ul-product-title"><a href="shop-details.php">Orange Airsuit</a></h4>
                                <h5 class="ul-product-category"><a href="shop.php">Fashion Bag</a></h5>
                            </div>
                        </div>
                    </div>

                    <!-- product card -->
                    <div class="swiper-slide">
                        <div class="ul-product">
                            <div class="ul-product-heading">
                                <span class="ul-product-price">₹99.00</span>
                                <span class="ul-product-discount-tag">25% Off</span>
                            </div>

                            <div class="ul-product-img">
                                <img src="assets/img/product-img-2.jpg" alt="Product Image">

                                <div class="ul-product-actions">
                                    <button><i class="flaticon-shopping-bag"></i></button>
                                    <a href="#"><i class="flaticon-hide"></i></a>
                                    <button><i class="flaticon-heart"></i></button>
                                </div>
                            </div>

                            <div class="ul-product-txt">
                                <h4 class="ul-product-title"><a href="shop-details.php">Orange Airsuit</a></h4>
                                <h5 class="ul-product-category"><a href="shop.php">Fashion Bag</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ul-sidebar-products-slider-nav flex-shrink-0">
                <button class="prev"><i class="flaticon-left-arrow"></i></button>
                <button class="next"><i class="flaticon-arrow-point-to-right"></i></button>
            </div>
        </div>

        <div class="ul-sidebar-about d-none d-lg-block">
            <p class="mb-0">Phasellus eget fermentum mauris. Suspendisse nec dignissim nulla. Integer non quam commodo,
                scelerisque felis id, eleifend turpis. Phasellus in nulla quis erat tempor tristique eget vel purus.
                Nulla pharetra pharetra pharetra. Praesent varius eget justo ut lacinia. Phasellus pharetra, velit
                viverra lacinia consequat, ipsum odio mollis dolor, nec facilisis arcu arcu ultricies sapien. Quisque ut
                dapibus nunc. Vivamus sit amet efficitur velit. Phasellus eget fermentum mauris. Suspendisse nec
                dignissim nulla. Integer non quam commodo, scelerisque felis id, eleifend turpis. Phasellus in nulla
                quis erat tempor tristique eget vel purus. Nulla pharetra pharetra pharetra. Praesent varius eget justo
                ut lacinia. Phasellus pharetra velit.</p>
        </div>

        <!-- sidebar footer -->
        <div class="ul-sidebar-footer">
            <span class="ul-sidebar-footer-title">Follow us</span>

            <div class="ul-sidebar-footer-social">
                <a href="#"><i class="flaticon-facebook-app-symbol"></i></a>
                <a href="#"><i class="flaticon-twitter"></i></a>
                <a href="#"><i class="flaticon-instagram"></i></a>
                <a href="#"><i class="flaticon-youtube"></i></a>
            </div>
        </div>
    </div>
    <!-- SIDEBAR SECTION END -->


    <!-- HEADER SECTION START -->
    <header class="ul-header">
        <!-- header top -->
        <div class="ul-header-top">
            <div class="ul-header-top-slider splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                        <li class="splide__slide">
                            <p class="ul-header-top-slider-item"><i class="flaticon-sparkle"></i> limited time offer</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- header bottom -->
        <div class="ul-header-bottom">
            <div class="ul-container">
                <div class="ul-header-bottom-wrapper">
                    <!-- header left -->
                    <div class="header-bottom-left">
                        <div class="logo-container">
                            <a href="index.php" class="d-inline-block"><img src="assets/img/logo.png" alt="logo"
                                    class="logo"></a>
                        </div>

                        <!-- search form -->
                        <div class="ul-header-search-form-wrapper flex-grow-1 flex-shrink-0">
                            <form action="shop.php" method="GET" class="ul-header-search-form">
                                <div class="dropdown-wrapper">
                                    <select name="category" id="ul-header-search-category">
                                        <option value="">Select Category</option>
                                        <option value="Man" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Man') ? 'selected' : ''; ?>>Man's Kurta</option>
                                        <option value="Woman" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Woman') ? 'selected' : ''; ?>>Woman's Kurta</option>
                                        <option value="Kids" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Kids') ? 'selected' : ''; ?>>Kid's Kurta</option>
                                        <!-- <option value="Clothing">Clothing</option>
                                        <option value="Watches">Watches</option>
                                        <option value="Jewellery">Jewellery</option>
                                        <option value="Glasses">Glasses</option> -->
                                    </select>
                                </div>
                                <div class="ul-header-search-form-right">
                                    <input type="search" name="product-search" id="ul-header-search"
                                        placeholder="Search Here">
                                    <button type="submit"><span class="icon"><i
                                                class="flaticon-search-interface-symbol"></i></span></button>
                                    <div class="ul-search-suggestions" id="ul-search-suggestions"></div>
                                </div>
                            </form>

                            <button class="ul-header-mobile-search-closer d-xxl-none"><i
                                    class="flaticon-close"></i></button>
                        </div>
                    </div>

                    <!-- header nav -->
                    <div class="ul-header-nav-wrapper">
                        <div class="to-go-to-sidebar-in-mobile">
                            <nav class="ul-header-nav">
                                <a href="index.php">Home</a>
                                <a href="shop.php">Shop</a>
                                <a href="shop.php?category=Woman">Women</a>
                                <a href="shop.php?category=Man">Men's</a>
                                <a href="shop.php?category=Kids">Kids</a>
                                <!-- <a href="blog.php">Blog</a> -->

                                <!-- <div class="has-sub-menu has-mega-menu">
                                    <a role="button">Pages</a>

                                    <div class="ul-header-submenu ul-header-megamenu">
                                        <div class="single-col">
                                            <span class="single-col-title">Inner Pages</span>
                                            <ul>
                                                <li><a href="about.php">About</a></li>
                                                <li><a href="blog.php">Blogs</a></li>
                                                <li><a href="blog-2.php">Blogs Layout 2</a></li>
                                                <li><a href="blog-details.php">Blog Details</a></li>
                                                <li><a href="contact.php">Contact</a></li>
                                                <li><a href="faq.php">FAQ</a></li>
                                                <li><a href="our-store.php">Our Store</a></li>
                                                <li><a href="reviews.php">Reviews</a></li>
                                                <?php if(isset($_SESSION['user_id'])): ?>
                                                    <li><a href="logout.php">Log Out</a></li>
                                                <?php else: ?>
                                                    <li><a href="login.php">Log In</a></li>
                                                    <li><a href="signup.php">Sign Up</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Shop Pages</span>
                                            <ul>
                                                <li><a href="shop.php">Shop Left Sidebar</a></li>
                                                <li><a href="shop-right-sidebar.php">Shop Right Sidebar</a></li>
                                                <li><a href="shop-no-sidebar.php">Shop Full Width</a></li>
                                                <li><a href="shop-details.php">Shop Details</a></li>
                                                <li><a href="wishlist.php">Wishlist</a></li>
                                                <li><a href="cart.php">Cart</a></li>
                                                <li><a href="checkout.php">Checkout</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Men's</span>
                                            <ul>
                                                <li><a href="#">Clothing</a></li>
                                                <li><a href="#">Footwear</a></li>
                                                <li><a href="#">Accessories</a></li>
                                                <li><a href="#">Activewear</a></li>
                                                <li><a href="#">Grooming</a></li>
                                                <li><a href="#">Ethnic Wear</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Women's</span>
                                            <ul>
                                                <li><a href="#">Clothing</a></li>
                                                <li><a href="#">Footwear</a></li>
                                                <li><a href="#">Bags & Accessories</a></li>
                                                <li><a href="#">Activewear</a></li>
                                                <li><a href="#">Beauty & Grooming</a></li>
                                                <li><a href="#">Ethnic Wear</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Children's</span>
                                            <ul>
                                                <li><a href="#">Clothing</a></li>
                                                <li><a href="#">Footwear</a></li>
                                                <li><a href="#">Accessories</a></li>
                                                <li><a href="#">Toys & Games</a></li>
                                                <li><a href="#">Baby Essentials</a></li>
                                            </ul>
                                        </div>

                                        <div class="single-col">
                                            <span class="single-col-title">Jewellery</span>
                                            <ul>
                                                <li><a href="#">Ethnic & Traditional Jewellery</a></li>
                                                <li><a href="#">Bridal Jewellery</a></li>
                                                <li><a href="#">Bracelets</a></li>
                                                <li><a href="#">Rings</a></li>
                                                <li><a href="#">Earrings</a></li>
                                                <li><a href="#">Chains & Pendants</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->
                            </nav>
                        </div>
                    </div>

                    <!-- actions -->
                    <div class="ul-header-actions">
                        <button class="ul-header-mobile-search-opener d-xxl-none"><i
                                class="flaticon-search-interface-symbol"></i></button>

                        <?php if(isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                            <div class="ul-header-user-wrapper">
                                <a href="javascript:void(0)" class="user-trigger">
                                    <?php if(isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="User" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;">
                                    <?php else: 
                                        $initials = isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 2)) : 'U';
                                    ?>
                                        <div style="width: 35px; height: 35px; background-color: #BF0A30; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                            <?php echo htmlspecialchars($initials); ?>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                <div class="user-dropdown">
                                    <span class="user-name">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                    <a href="order_history.php" class="logout-btn" style="border-top: 1px solid #eee; margin-top: 5px; padding-top: 5px;">Order History</a>
                                    <a href="logout.php" class="logout-btn">Log Out</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="login.php" title="Log In"><i class="flaticon-user"></i></a>
                        <?php endif; ?>

                        <?php
                        $wishlist_count = 0;
                        if(isset($_SESSION['user_id']) && isset($conn)){
                            $uid_header = $_SESSION['user_id'];
                            $wish_sql = "SELECT COUNT(*) as total FROM wishlist WHERE user_id = '$uid_header'";
                            $wish_res = $conn->query($wish_sql);
                            if($wish_res && $wish_row = $wish_res->fetch_assoc()){
                                $wishlist_count = $wish_row['total'] ?? 0;
                            }
                        }
                        ?>
                        <a href="wishlist.php" class="ul-header-wishlist-icon">
                            <i class="flaticon-heart"></i>
                            <?php if($wishlist_count > 0): ?>
                            <span class="ul-header-cart-count" style="background-color: #BF0A30;"><?php echo $wishlist_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <?php
                        $cart_count = 0;
                        if(isset($_SESSION['user_id']) && isset($conn)){
                            $uid_header = $_SESSION['user_id'];
                            $cart_sql = "SELECT SUM(quantity) as total FROM cart WHERE user_id = '$uid_header'";
                            $cart_res = $conn->query($cart_sql);
                            if($cart_res && $cart_row = $cart_res->fetch_assoc()){
                                $cart_count = $cart_row['total'] ?? 0;
                            }
                        }
                        ?>
                        <a href="cart.php" class="ul-header-cart-icon">
                            <i class="flaticon-shopping-bag"></i>
                            <?php if($cart_count > 0): ?>
                            <span class="ul-header-cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>

                    <!-- sidebar opener -->
                    <div class="d-inline-flex">
                        <button class="ul-header-sidebar-opener"><i class="flaticon-hamburger"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER SECTION END -->