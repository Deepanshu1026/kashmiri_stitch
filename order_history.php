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
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
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
                                                    $badgeClass = 'bg-danger';
                                                    $displayStatus = 'Payment Failed';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $displayStatus; ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['razorpay_payment_id'] ?? 'N/A'); ?></td>
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
