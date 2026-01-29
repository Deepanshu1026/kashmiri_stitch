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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">#<?php echo htmlspecialchars($row['id']); ?></span>
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
                                                    $item_count = 0;
                                                    $first_item_name = "";
                                                    
                                                    if($items_res && $items_res->num_rows > 0){
                                                        echo '<div class="d-flex align-items-center gap-2">';
                                                        // Limit visual items to avoidance clutter
                                                        while($item = $items_res->fetch_assoc()){
                                                            $item_count++;
                                                            if($item_count == 1) $first_item_name = $item['title'];
                                                            
                                                            if($item_count <= 3) {
                                                                $img_src = !empty($item['image']) ? $item['image'] : 'assets/img/product-img-1.jpg';
                                                                echo '<img src="'.$img_src.'" alt="Product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; border: 1px solid #eee;">';
                                                            }
                                                        }
                                                        if($item_count > 3) {
                                                            echo '<span class="badge bg-light text-dark border">+'.($item_count - 3).'</span>';
                                                        }
                                                        
                                                        // Text summary
                                                        echo '<div class="ms-2 d-none d-md-block">';
                                                        echo '<small class="d-block fw-bold text-dark text-truncate" style="max-width: 150px;">'.$first_item_name.'</small>';
                                                        if($item_count > 1) echo '<small class="text-muted">and '.($item_count - 1).' more items</small>';
                                                        echo '</div>';
                                                        
                                                        echo '</div>';
                                                    } else {
                                                        echo '<span class="text-muted small">No details available</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="fw-bold">₹<?php echo number_format($row['amount'], 2); ?></td>
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
                                                <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-2"><?php echo $displayStatus; ?></span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger view-details-btn" data-order-id="<?php echo $row['id']; ?>">
                                                    <i class="flaticon-search"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="flaticon-shopping-bag display-1 text-muted opacity-25"></i>
                            </div>
                            <h3 class="fw-bold">No Orders Yet</h3>
                            <p class="text-muted mb-4">Looks like you haven't made your first purchase yet.</p>
                            <a href="shop.php" class="btn btn-danger btn-lg px-5">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">Order Details <span id="modal-order-id" class="opacity-75 ms-2 small"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modal-body-content">
                <div class="text-center py-5"><div class="spinner-border text-danger" role="status"></div></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark" id="reorderBtn"><i class="flaticon-shopping-bag"></i> Buy Again</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailBtns = document.querySelectorAll('.view-details-btn');
    const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
    const modalBody = document.getElementById('modal-body-content');
    const modalTitleId = document.getElementById('modal-order-id');
    const reorderBtn = document.getElementById('reorderBtn');
    let currentOrderId = null;

    reorderBtn.addEventListener('click', function() {
        if(!currentOrderId) return;
        
        if(confirm('Add all items from this order to your cart?')) {
            const formData = new FormData();
            formData.append('order_id', currentOrderId);
            
            fetch('reorder.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    if(confirm(data.message + '. Go to cart?')) {
                        window.location.href = 'cart.php';
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        }
    });

    detailBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            currentOrderId = orderId;
            modalTitleId.innerText = '#' + orderId;
            modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-danger" role="status"></div><p class="mt-2 text-muted">Loading details...</p></div>';
            modal.show();

            fetch('get_order_details.php?order_id=' + orderId)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    const o = data.order;
                    const items = data.items;
                    
                    let itemsHtml = '';
                    items.forEach(item => {
                        let variants = '';
                        if(item.size) variants += `<span class="badge bg-light text-dark border me-1">Size: ${item.size}</span>`;
                        if(item.color) variants += `<span class="badge bg-light text-dark border">Color: ${item.color}</span>`;

                        // Add link to product page (Redirection)
                        let productLink = `shop-details.php?id=${item.product_id}`;

                        itemsHtml += `
                            <div class="d-flex align-items-center border-bottom py-3">
                                <a href="${productLink}">
                                    <img src="${item.image || 'assets/img/product-img-1.jpg'}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                </a>
                                <div class="ms-3 flex-grow-1">
                                    <a href="${productLink}" class="text-decoration-none text-dark">
                                        <h6 class="mb-1 fw-bold hover-link">${item.title}</h6>
                                    </a>
                                    <div class="mb-1">${variants}</div>
                                    <small class="text-muted">Qty: ${item.quantity} x ₹${item.price}</small>
                                </div>
                                <div class="fw-bold">₹${(item.quantity * item.price).toFixed(2)}</div>
                            </div>
                        `;
                    });

                    modalBody.innerHTML = `
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted small fw-bold mb-2">Billing Address</h6>
                                <div class="p-3 bg-light rounded">
                                    <strong>${o.firstname} ${o.lastname}</strong><br>
                                    ${o.address1}<br>
                                    ${o.address2 ? o.address2 + '<br>' : ''}
                                    ${o.city}, ${o.state} - ${o.zipcode}<br>
                                    Phone: ${o.phone}
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <h6 class="text-uppercase text-muted small fw-bold mb-2">Order Summary</h6>
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Order Date:</span>
                                        <strong>${new Date(o.created_at).toLocaleDateString()}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Payment Status:</span>
                                        <span class="badge bg-${(o.status === 'captured' || o.status === 'paid') ? 'success' : 'warning'}">${o.status}</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                        <span class="h5 mb-0">Total Amount:</span>
                                        <span class="h5 mb-0 text-danger">₹${Number(o.amount).toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Items Ordered</h6>
                        <div class="border rounded px-3">
                            ${itemsHtml}
                        </div>
                    `;
                } else {
                    modalBody.innerHTML = '<div class="alert alert-danger">Error loading details.</div>';
                }
            })
            .catch(err => {
                console.error(err);
                modalBody.innerHTML = '<div class="alert alert-danger">Failed to fetch details.</div>';
            });
        });
    });
});
</script>

<?php include 'footer.php'; ?>
