<?php 
include 'config/db_connect.php';
include 'header.php';

if(!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id as cart_id, c.quantity, c.size, c.color, p.id as product_id, p.title, p.price, p.image, p.category 
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
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
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
                            // Store items for reuse in mobile view
                            $cart_items = [];
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $cart_items[] = $row;
                                }
                            }
                            
                            $subtotal = 0;
                            if (count($cart_items) > 0) {
                                foreach($cart_items as $row) {
                                    $item_subtotal = $row['price'] * $row['quantity'];
                                    $subtotal += $item_subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="ul-cart-product">
                                        <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="ul-cart-product-img"><img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"></a>
                                        <div class="ul-cart-product-info">
                                            <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="ul-cart-product-title"><?php echo htmlspecialchars($row['title']); ?></a>
                                            <div class="ul-cart-product-variants" style="margin-top: 5px; font-size: 13px; color: #777;">
                                                <?php if(!empty($row['size'])): ?>
                                                    <div style="margin-bottom: 2px;">Size: <span style="font-weight: 500; color: #333;"><?php echo htmlspecialchars($row['size']); ?></span></div>
                                                <?php endif; ?>
                                                <?php if(!empty($row['color'])): ?>
                                                    <div>Color: <span style="font-weight: 500; color: #333;"><?php echo htmlspecialchars($row['color']); ?></span></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="ul-cart-item-price">₹<?php echo $row['price']; ?></span></td>
                                <td>
                                    <div class="ul-product-details-quantity mt-0">
                                        <div class="ul-product-quantity-wrapper">
                                            <input type="number" name="product-quantity" class="ul-product-quantity cart-qty-input" value="<?php echo $row['quantity']; ?>" min="1" data-cart-id="<?php echo $row['cart_id']; ?>" readonly>
                                            <div class="btns">
                                                <button type="button" class="quantityIncreaseButton cart-qty-btn" data-action="increase"><i class="flaticon-plus"></i></button>
                                                <button type="button" class="quantityDecreaseButton cart-qty-btn" data-action="decrease"><i class="flaticon-minus-sign"></i></button>
                                            </div>
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

                <!-- Mobile Card View (Vertical) - New UI -->
                <div class="d-md-none">
                    <?php
                    if (count($cart_items) > 0) {
                        foreach($cart_items as $row) {
                            $item_subtotal = $row['price'] * $row['quantity'];
                    ?>
                    <div class="d-flex align-items-start mb-4 p-3 bg-white" style="border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <!-- Image Container with Badge -->
                        <div class="position-relative flex-shrink-0" style="width: 100px; height: 100px;">
                             <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                  style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; border: 1px solid #f0f0f0;">
                             <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" 
                                   style="font-size: 10px; border: 2px solid white;">
                                <?php echo $row['quantity']; ?>
                             </span>
                        </div>
                        
                        <!-- Content -->
                        <div class="ms-3 flex-grow-1 d-flex flex-column justify-content-between" style="min-height: 100px;">
                             <div>
                                 <h4 style="font-size: 15px; font-weight: 600; margin-bottom: 2px; line-height: 1.3;">
                                     <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="text-dark text-decoration-none">
                                         <?php echo htmlspecialchars($row['title']); ?>
                                     </a>
                                 </h4>
                                 <div class="text-muted small mb-1" style="font-size: 12px;">
                                     <?php echo htmlspecialchars($row['category'] ?? 'Product'); ?>
                                     <?php if(!empty($row['color'])) echo ' • Color: ' . htmlspecialchars($row['color']); ?>
                                 </div>
                                 <?php if(!empty($row['size'])): ?>
                                     <div class="text-muted small" style="font-size: 12px;">Size: <?php echo htmlspecialchars($row['size']); ?></div>
                                 <?php endif; ?>
                             </div>

                             <div class="d-flex justify-content-between align-items-end mt-2">
                                 <div class="fw-bold fs-5 text-dark">₹<?php echo $row['price']; ?></div>
                                 
                                 <div class="d-flex align-items-center">
                                     <!-- Stepper -->
                                     <div class="ul-product-quantity-wrapper" style="width: auto; height: 32px; border: 1px solid #eee; border-radius: 6px; display: flex; align-items: center; padding: 0 5px; background: #fff;">
                                         <button type="button" class="cart-qty-btn border-0 bg-transparent p-1" data-action="decrease" style="color: #999;"><i class="flaticon-minus-sign" style="font-size: 10px;"></i></button>
                                         <input type="number" class="cart-qty-input border-0 text-center p-0" value="<?php echo $row['quantity']; ?>" min="1" data-cart-id="<?php echo $row['cart_id']; ?>" readonly style="width: 30px; font-weight: 500; font-size: 14px;">
                                         <button type="button" class="cart-qty-btn border-0 bg-transparent p-1" data-action="increase" style="color: #333;"><i class="flaticon-plus" style="font-size: 10px;"></i></button>
                                     </div>
                                     
                                     <!-- Delete Icon -->
                                     <a href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>" class="ms-3 text-warning" style="opacity: 0.7;">
                                         <!-- Trash Icon SVG -->
                                         <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#F49D37" class="bi bi-trash" viewBox="0 0 16 16">
                                             <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                             <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                         </svg>
                                     </a>
                                 </div>
                             </div>
                        </div>
                    </div>
                    <?php 
                        }
                    } else {
                        echo "<div class='text-center py-4'>Your cart is empty. <a href='shop.php'>Go Shopping</a></div>";
                    }
                    ?>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const qtyBtns = document.querySelectorAll('.cart-qty-btn');
                    
                    qtyBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const wrapper = this.closest('.ul-product-quantity-wrapper');
                            const input = wrapper.querySelector('.cart-qty-input');
                            let currentVal = parseInt(input.value);
                            const action = this.getAttribute('data-action');
                            const cartId = input.getAttribute('data-cart-id');

                            if(action === 'increase') {
                                currentVal++;
                            } else if(action === 'decrease') {
                                if(currentVal > 1) currentVal--;
                            }

                            // Optimistic UI update
                            input.value = currentVal;
                            
                            // Send AJAX request
                            const formData = new FormData();
                            formData.append('cart_id', cartId);
                            formData.append('quantity', currentVal);
                            
                            // Disable buttons while processing
                            const allBtns = wrapper.querySelectorAll('button');
                            allBtns.forEach(b => b.disabled = true);

                            fetch('update_cart_quantity.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.status === 'success') {
                                    location.reload(); // Reload to update subtotals/totals
                                } else {
                                    alert(data.message);
                                    location.reload();
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                location.reload();
                            });
                        });
                    });
                });
                </script>

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