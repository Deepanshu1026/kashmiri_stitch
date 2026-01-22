<?php 
include 'config/db_connect.php';
include 'header.php';

if(!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id as cart_id, c.quantity, p.id as product_id, p.title, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = '$user_id'";
$result = $conn->query($sql);
?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Cart List</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.html"><i class="flaticon-home"></i> Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Cart List</span>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB SECTION END -->


        <div class="ul-cart-container">
            <div class="cart-top">
                <div class="table-responsive">
                    <table class="ul-cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $subtotal = 0;
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $item_subtotal = $row['price'] * $row['quantity'];
                                    $subtotal += $item_subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="ul-cart-product">
                                        <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="ul-cart-product-img"><img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"></a>
                                        <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="ul-cart-product-title"><?php echo htmlspecialchars($row['title']); ?></a>
                                    </div>
                                </td>
                                <td><span class="ul-cart-item-price">₹<?php echo $row['price']; ?></span></td>
                                <td>
                                    <div class="ul-product-details-quantity mt-0">
                                        <div class="ul-product-quantity-wrapper">
                                            <input type="number" name="product-quantity" class="ul-product-quantity" value="<?php echo $row['quantity']; ?>" min="1" readonly>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="ul-cart-item-subtotal">₹<?php echo number_format($item_subtotal, 2); ?></span></td>
                                <td>
                                    <div class="ul-cart-item-remove"><a href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>"><button><i class="flaticon-close"></i></button></a></div>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Your cart is empty. <a href='shop.php'>Go Shopping</a></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="ul-cart-actions">
                        <div class="ul-cart-coupon-code-form-wrapper">
                            <!-- <span class="title">Coupon:</span>
                            <form action="#" class="ul-cart-coupon-code-form">
                                <input name="coupon-code" placeholder="Enter Coupon Code" type="text">
                                <button class="mb-btn">Apply</button>
                            </form> -->
                        </div>

                        <!-- <button class="ul-cart-update-cart-btn">Update Cart</button> -->
                    </div>
                </div>
            </div>

            <div class="cart-bottom">
                <div class="ul-cart-expense-overview">
                    <h3 class="ul-cart-expense-overview-title">Total</h3>
                    <div class="middle">
                        <div class="single-row">
                            <span class="inner-title">Subtotal</span>
                            <span class="number">₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>

                        <div class="single-row">
                            <span class="inner-title">Shipping Fee</span>
                            <span class="number">Free</span>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="single-row">
                            <span class="inner-title">Total</span>
                            <span class="number">₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>

                        <a href="checkout.php"><button class="ul-cart-checkout-direct-btn">CHECKOUT</button></a>
                    </div>
                </div>
            </div>
        </div>
    </main>

  <?php include 'footer.php'; ?>