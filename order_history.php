<?php
include 'config/db_connect.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="ul-container">
    <div class="row mt-5 mb-5 justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0 text-white">My Order History</h4>
                </div>
                <div class="card-body">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($row['id']); ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($row['razorpay_order_id']); ?></small>
                                            </td>
                                            <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <?php
                                                    $order_id = $row['id'];
                                                    // Fetch items for this order
                                                    $items_sql = "SELECT oi.*, p.title, p.image, p.id as product_id 
                                                                  FROM order_items oi 
                                                                  JOIN products p ON oi.product_id = p.id 
                                                                  WHERE oi.order_id = '$order_id'";
                                                    $items_res = $conn->query($items_sql);
                                                    if($items_res && $items_res->num_rows > 0){
                                                        echo '<div class="d-flex flex-wrap gap-2">';
                                                        while($item = $items_res->fetch_assoc()){
                                                            $img_src = !empty($item['image']) ? $item['image'] : 'assets/img/product-img-1.jpg'; // Fallback
                                                            $p_url = "shop-details.php?id=" . $item['product_id'];
                                                            echo '<a href="'.$p_url.'" title="'.htmlspecialchars($item['title']).' (Qty: '.$item['quantity'].')">
                                                                    <img src="'.$img_src.'" alt="'.htmlspecialchars($item['title']).'" 
                                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #eee;">
                                                                  </a>';
                                                        }
                                                        echo '</div>';
                                                    } else {
                                                        echo '<span class="text-muted">No details available</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                                            <td>
                                                <?php 
                                                $status = $row['status'];
                                                $badgeClass = 'bg-secondary';
                                                $displayStatus = ucfirst($status);
                                                
                                                if ($status == 'captured' || $status == 'paid') {
                                                    $badgeClass = 'bg-success';
                                                } elseif ($status == 'failed') {
                                                    $badgeClass = 'bg-danger';
                                                } elseif ($status == 'created') {
                                                    $badgeClass = 'bg-warning text-dark';
                                                    $displayStatus = 'Pending Payment';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $displayStatus; ?></span>
                                            </td>
                                            <td>
                                                <?php if($status == 'failed' || $status == 'created'): ?>
                                                    <!-- Optional: specific action for failed orders? -->
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="flaticon-shopping-bag display-1 text-muted mb-3"></i>
                            <h3>No Orders Yet</h3>
                            <p class="text-muted">You haven't placed any orders yet.</p>
                            <a href="shop.php" class="btn btn-danger mt-3">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
