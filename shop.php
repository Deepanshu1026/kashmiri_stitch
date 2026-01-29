<?php
$page_title = "Shop";
include 'config/db_connect.php';
include 'header.php';
?>
?>
<style>
    /* Force product actions to be visible by default */
    .ul-product-actions.active-visible {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(-50%) translateX(-50%) !important;
        bottom: 40% !important; /* Adjust based on original design, usually centered or bottom */
        left: 50% !important;
        display: flex;
        gap: 10px;
    }
    
    .ul-product:hover .ul-product-actions.active-visible {
        /* Maintain visibility on hover */
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(-50%) translateX(-50%) !important;
    }
</style>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Shop Left Sidebar</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.php"><i class="flaticon-home"></i> Home</a>
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
                    <!-- left side bar -->
                    <div class="col-lg-3 col-md-4">
                        <div class="ul-products-sidebar">
                            <!-- single widget / search -->
                            <div class="ul-products-sidebar-widget ul-products-search">
                                <form action="shop.php" method="GET" class="ul-products-search-form">
                                    <input type="text" name="product-search" id="ul-products-search-field" placeholder="Search Items" value="<?php echo isset($_GET['product-search']) ? htmlspecialchars($_GET['product-search']) : ''; ?>">
                                    <button type="submit"><i class="flaticon-search-interface-symbol"></i></button>
                                </form>
                            </div>

                            <!-- single widget / price filter -->
                            <div class="ul-products-sidebar-widget ul-products-price-filter">
                                <h3 class="ul-products-sidebar-widget-title">Filter by price</h3>
                                <form action="#" class="ul-products-price-filter-form">
                                    <div id="ul-products-price-filter-slider"></div>
                                    <?php
                                    // Get min and max price
                                    $price_sql = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM products";
                                    $price_res = $conn->query($price_sql);
                                    $min_price = 0;
                                    $max_price = 1000;
                                    if ($price_res && $price_row = $price_res->fetch_assoc()) {
                                        $min_price = floor($price_row['min_price']);
                                        $max_price = ceil($price_row['max_price']);
                                    }
                                    ?>
                                    <span class="filtered-price">₹<?php echo $min_price; ?> - ₹<?php echo $max_price; ?></span>
                                </form>
                            </div>

                            <!-- single widget / categories -->
                            <div class="ul-products-sidebar-widget ul-products-categories">
                                <h3 class="ul-products-sidebar-widget-title">Categories</h3>
                                <div class="ul-products-categories-link">
                                    <a href="shop.php"><span><i class="flaticon-arrow-point-to-right"></i> All Categories</span></a>
                                    <?php
                                    $cat_sql = "SELECT DISTINCT category FROM products ORDER BY category";
                                    $cat_res = $conn->query($cat_sql);
                                    if ($cat_res->num_rows > 0) {
                                        while($cat_row = $cat_res->fetch_assoc()) {
                                            $active = (isset($_GET['category']) && $_GET['category'] == $cat_row['category']) ? 'style="color: var(--ul-primary-color);"' : '';
                                            echo '<a href="shop.php?category='.urlencode($cat_row['category']).'" '.$active.'><span><i class="flaticon-arrow-point-to-right"></i> '.htmlspecialchars($cat_row['category']).'</span></a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- single widget /product status-->
                            <div class="ul-products-sidebar-widget">
                                <h3 class="ul-products-sidebar-widget-title">Product Status</h3>
                                <div class="ul-products-categories-link">
                                    <a href="shop.php"><span><i class="flaticon-arrow-point-to-right"></i> All Status</span></a>
                                    <a href="shop.php?status=featured"><span><i class="flaticon-arrow-point-to-right"></i> Featured</span></a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- right products container -->
                    <div class="col-lg-9 col-md-8">
                        <div class="row ul-bs-row row-cols-lg-3 row-cols-2 row-cols-xxs-1">
                            <?php
                            $where_clauses = [];
                            
                            // Category Filter
                            if (isset($_GET['category']) && !empty($_GET['category'])) {
                                $cat_filter = $conn->real_escape_string($_GET['category']);
                                $where_clauses[] = "category = '$cat_filter'";
                            }

                            // Search Filter
                            if (isset($_GET['product-search']) && !empty($_GET['product-search'])) {
                                $search_query = $conn->real_escape_string($_GET['product-search']);
                                $where_clauses[] = "(title LIKE '%$search_query%' OR description LIKE '%$search_query%')";
                            }

                            // Status Filter
                            if (isset($_GET['status']) && $_GET['status'] == 'featured') {
                                // Assuming we might want to support is_featured if column exists
                                // In previous schema view, is_featured BOOLEAN existed.
                                $where_clauses[] = "is_featured = 1";
                            }


                            $sql = "SELECT * FROM products";
                            if (!empty($where_clauses)) {
                                $sql .= " WHERE " . implode(" AND ", $where_clauses);
                            }
                            $sql .= " ORDER BY id DESC";

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
                                            </div>

                                            <div class="ul-product-txt">
                                                <h4 class="ul-product-title"><a href="shop-details.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h4>
                                                <h5 class="ul-product-category"><a href="shop.php?category=<?php echo urlencode($row['category']); ?>"><?php echo htmlspecialchars($row['category']); ?></a></h5>
                                            </div>

                                            <div class="ul-product-actions-static" style="display: flex; gap: 10px; margin-top: 15px;">
                                                <a href="shop-details.php?id=<?php echo $row['id']; ?>" class="ul-btn-action" style="
                                                    width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                                    border: 1px solid #eee; color: #333; transition: 0.3s; background: #fff;">
                                                    <i class="flaticon-shopping-bag"></i>
                                                </a>
                                                <button class="add-to-wishlist ul-btn-action" data-pid="<?php echo $row['id']; ?>" style="
                                                    width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                                    border: 1px solid #eee; color: #333; transition: 0.3s; background: #fff;">
                                                    <i class="flaticon-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<div class='col-12'><p>No products found matching your criteria.</p></div>";
                            }
                            ?>
                        </div>

                        <!-- pagination -->
                        <div class="ul-pagination">
                            <ul>
                                <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                <li class="pages">
                                    <a href="#" class="active">01</a>
                                    <!-- Dynamic pagination to be implemented later -->
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