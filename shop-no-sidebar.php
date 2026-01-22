


    <?php include 'header.php'; ?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Shop Without Sidebar</h2>
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
                <div class="row ul-bs-row flex-column-reverse flex-md-row">
                    <!-- right products container -->
                    <div class="col-12">
                        <div class="row ul-bs-row row-cols-lg-4 row-cols-sm-3 row-cols-2 row-cols-xxs-1">
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
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT SECTION END -->
    </main>

  <?php include 'footer.php'; ?>