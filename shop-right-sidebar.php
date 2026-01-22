
    <?php include 'header.php'; ?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Shop Right Sidebar</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.html"><i class="flaticon-home"></i> Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Shop</span>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB SECTION END -->

        <!-- MAIN CONTENT SECTION START -->
        <div class="ul-inner-page-container">
            <div class="ul-inner-products-wrapper">
                <div class="row ul-bs-row flex-column flex-md-row">

                    <!-- right products container -->
                    <div class="col-lg-9 col-md-8">
                        <div class="row ul-bs-row row-cols-lg-3 row-cols-2 row-cols-xxs-1">
                            <?php
                            include_once 'config/db_connect.php'; // Ensure db connection
                            
                            $sql = "SELECT * FROM products ORDER BY id DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    ?>
                                    <!-- product card -->
                                    <div class="col">
                                        <div class="ul-product">
                                            <div class="ul-product-heading">
                                                <span class="ul-product-price">₹<?php echo $row['price']; ?></span>
                                                <!-- <span class="ul-product-discount-tag">25% Off</span> -->
                                            </div>

                                            <div class="ul-product-img">
                                                <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">

                                                <div class="ul-product-actions">
                                                    <a href="shop-details.php?id=<?php echo $row['id']; ?>"><button><i class="flaticon-shopping-bag"></i></button></a>
                                                    <a href="shop-details.php?id=<?php echo $row['id']; ?>"><i class="flaticon-hide"></i></a>
                                                    <button class="add-to-wishlist" data-pid="<?php echo $row['id']; ?>"><i class="flaticon-heart"></i></button>
                                                </div>
                                            </div>

                                            <div class="ul-product-txt">
                                                <h4 class="ul-product-title"><a href="shop-details.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h4>
                                                <h5 class="ul-product-category"><a href="shop.php?category=<?php echo urlencode($row['category']); ?>"><?php echo htmlspecialchars($row['category']); ?></a></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<div class='col-12'><p>No products found.</p></div>";
                            }
                            ?>
                        </div>

                        <!-- pagination -->
                        <div class="ul-pagination">
                            <ul>
                                <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                <li class="pages">
                                    <a href="#" class="active">01</a>
                                    <a href="#">02</a>
                                    <a href="#">03</a>
                                    <a href="#">04</a>
                                    <a href="#">05</a>
                                </li>
                                <li><a href="#"><i class="flaticon-arrow-point-to-right"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- left side bar -->
                    <div class="col-lg-3 col-md-4">
                        <div class="ul-products-sidebar">
                            <!-- single widget / search -->
                            <div class="ul-products-sidebar-widget ul-products-search">
                                <form action="#" class="ul-products-search-form">
                                    <input type="text" name="product-search" id="ul-products-search-field" placeholder="Search Items">
                                    <button><i class="flaticon-search-interface-symbol"></i></button>
                                </form>
                            </div>

                            <!-- single widget / price filter -->
                            <div class="ul-products-sidebar-widget ul-products-price-filter">
                                <h3 class="ul-products-sidebar-widget-title">Filter by price</h3>
                                <form action="#" class="ul-products-price-filter-form">
                                    <div id="ul-products-price-filter-slider"></div>
                                    <span class="filtered-price">₹19 - ₹69</span>
                                </form>
                            </div>

                            <!-- single widget / categories -->
                            <div class="ul-products-sidebar-widget ul-products-categories">
                                <h3 class="ul-products-sidebar-widget-title">Categories</h3>
                                <div class="ul-products-categories-link">
                                    <a href="shop.html"><span><i class="flaticon-arrow-point-to-right"></i> Women</span></a>
                                    <a href="shop.html"><span><i class="flaticon-arrow-point-to-right"></i> Men</span></a>
                                    <a href="shop.html"><span><i class="flaticon-arrow-point-to-right"></i> Kids</span></a>
                                </div>
                            </div>

                            <!-- single widget / color filter -->
                            <div class="ul-products-sidebar-widget ul-products-color-filter">
                                <h3 class="ul-products-sidebar-widget-title">Filter By Color</h3>

                                <div class="ul-products-color-filter-colors">
                                    <a href="shop.html" class="black">
                                        <span class="left"><span class="color-prview"></span> Black</span>
                                        <span>14</span>
                                    </a>
                                    <a href="shop.html" class="green">
                                        <span class="left"><span class="color-prview"></span> Green</span>
                                        <span>14</span>
                                    </a>
                                    <a href="shop.html" class="blue">
                                        <span class="left"><span class="color-prview"></span> Blue</span>
                                        <span>14</span>
                                    </a>
                                    <a href="shop.html" class="red">
                                        <span class="left"><span class="color-prview"></span> Red</span>
                                        <span>14</span>
                                    </a>
                                    <a href="shop.html" class="yellow">
                                        <span class="left"><span class="color-prview"></span> Yellow</span>
                                        <span>14</span>
                                    </a>
                                    <a href="shop.html" class="brown">
                                        <span class="left"><span class="color-prview"></span> Brown</span>
                                        <span>14</span>
                                    </a>
                                    <a href="shop.html" class="white">
                                        <span class="left"><span class="color-prview"></span> White</span>
                                        <span>14</span>
                                    </a>
                                </div>
                            </div>

                            <!-- single widget /product status-->
                            <div class="ul-products-sidebar-widget">
                                <h3 class="ul-products-sidebar-widget-title">Product Status</h3>

                                <div class="ul-products-categories-link">
                                    <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> In stock</span></a>
                                    <a href="#"><span><i class="flaticon-arrow-point-to-right"></i> On Sale</span></a>
                                </div>
                            </div>

                            <!-- single widget / size filter -->
                            <div class="ul-products-sidebar-widget">
                                <h3 class="ul-products-sidebar-widget-title">Filter By Sizes</h3>

                                <div class="ul-products-color-filter-colors">
                                    <a href="shop.html"><span class="left">S</span><span>14</span></a>
                                    <a href="shop.html"><span class="left">L</span><span>14</span></a>
                                    <a href="shop.html"><span class="left">M</span><span>14</span></a>
                                    <a href="shop.html"><span class="left">XL</span><span>14</span></a>
                                    <a href="shop.html"><span class="left">XXL</span><span>14</span></a>
                                </div>
                            </div>

                            <!-- single widget / review -->
                            <div class="ul-products-sidebar-widget ul-products-rating-filter">
                                <h3 class="ul-products-sidebar-widget-title">Review Star</h3>

                                <div class="ul-products-rating-filter-ratings">
                                    <!-- single rating filter -->
                                    <div class="single-rating-wrapper">
                                        <label for="ul-products-review-5-star">
                                            <span class="left">
                                                <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-5-star" hidden>
                                                <span class="stars">
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                </span>
                                            </span>
                                            <span class="right">5 Only</span>
                                        </label>
                                    </div>

                                    <!-- single rating filter -->
                                    <div class="single-rating-wrapper">
                                        <label for="ul-products-review-4-star">
                                            <span class="left">
                                                <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-4-star" hidden>
                                                <span class="stars">
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                </span>
                                            </span>
                                            <span class="right">4 & up</span>
                                        </label>
                                    </div>

                                    <!-- single rating filter -->
                                    <div class="single-rating-wrapper">
                                        <label for="ul-products-review-3-star">
                                            <span class="left">
                                                <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-3-star" hidden>
                                                <span class="stars">
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                </span>
                                            </span>
                                            <span class="right">3 & up</span>
                                        </label>
                                    </div>

                                    <!-- single rating filter -->
                                    <div class="single-rating-wrapper">
                                        <label for="ul-products-review-2-star">
                                            <span class="left">
                                                <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-2-star" hidden>
                                                <span class="stars">
                                                    <span><i class="flaticon-star"></i></span>
                                                    <span><i class="flaticon-star"></i></span>
                                                </span>
                                            </span>
                                            <span class="right">2 & up</span>
                                        </label>
                                    </div>

                                    <!-- single rating filter -->
                                    <div class="single-rating-wrapper">
                                        <label for="ul-products-review-1-star">
                                            <span class="left">
                                                <input type="checkbox" name="jo-checkout-agreement" id="ul-products-review-1-star" hidden>
                                                <span class="stars">
                                                    <span><i class="flaticon-star"></i></span>
                                                </span>
                                            </span>
                                            <span class="right">1 & up</span>
                                        </label>
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

      <?php include 'footer.php'; ?>