<?php 
include 'config/db_connect.php';
include 'header.php';

if(!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT w.id as wishlist_id, p.id as product_id, p.title, p.price, p.image, p.category 
        FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = '$user_id'
        ORDER BY w.created_at DESC";
$result = $conn->query($sql);
?>

    <main>
        <!-- BREADCRUMB SECTION START -->
        <div class="ul-container">
            <div class="ul-breadcrumb">
                <h2 class="ul-breadcrumb-title">Wishlist</h2>
                <div class="ul-breadcrumb-nav">
                    <a href="index.php"><i class="flaticon-home"></i> Home</a>
                    <i class="flaticon-arrow-point-to-right"></i>
                    <span class="current-page">Wishlist</span>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB SECTION END -->

        <div class="ul-inner-page-container">
            <div class="ul-cart-container" style="max-width: 100%; margin: 0;">
                <div class="cart-top">
                    <div class="table-responsive">
                        <table class="ul-cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="ul-cart-product">
                                            <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="ul-cart-product-img"><img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>"></a>
                                            <a href="shop-details.php?id=<?php echo $row['product_id']; ?>" class="ul-cart-product-title"><?php echo htmlspecialchars($row['title']); ?></a>
                                        </div>
                                    </td>
                                    <td><span class="ul-cart-item-price">₹<?php echo $row['price']; ?></span></td>
                                    <td><span><?php echo htmlspecialchars($row['category']); ?></span></td>
                                    <td>
                                        <form action="add_to_cart.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="ul-btn" style="height: 40px; padding: 0 20px; font-size: 14px;">Add to Cart</button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="ul-cart-item-remove">
                                            <button class="remove-wishlist-btn" data-pid="<?php echo $row['product_id']; ?>" style="color: var(--ul-primary);"><i class="flaticon-close"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>Your wishlist is empty. <a href='shop.php'>Go Shopping</a></td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove from wishlist logic
        const removeBtns = document.querySelectorAll('.remove-wishlist-btn');
        removeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const pid = this.getAttribute('data-pid');
                const row = this.closest('tr');
                
                if(confirm('Are you sure you want to remove this item from wishlist?')) {
                    const formData = new FormData();
                    formData.append('product_id', pid);

                    fetch('remove_from_wishlist.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            row.remove();
                            if(document.querySelectorAll('tbody tr').length === 0) {
                                document.querySelector('tbody').innerHTML = "<tr><td colspan='5' class='text-center'>Your wishlist is empty. <a href='shop.php'>Go Shopping</a></td></tr>";
                            }
                            // Optional: Update header count if you implement it
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    });
    </script>

   <?php include 'footer.php'; ?>