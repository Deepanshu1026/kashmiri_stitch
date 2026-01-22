<?php 
include '../config/db_connect.php';
include 'includes/header.php'; 

// Fetch Stats
$product_count = 0;
$cat_count = 0;
$total_price_inventory = 0;

$p_sql = "SELECT COUNT(*) as count, COUNT(DISTINCT category) as cats, SUM(price) as total_val FROM products";
$result = $conn->query($p_sql);
if($result && $row = $result->fetch_assoc()){
    $product_count = $row['count'];
    $cat_count = $row['cats'];
    $total_price_inventory = $row['total_val'];
}

?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="stat-card">
            <div class="info">
                <h3><?php echo $product_count; ?></h3>
                <p>Total Products</p>
            </div>
            <div class="icon"><i class="flaticon-shopping-bag"></i></div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stat-card">
            <div class="info">
                <h3><?php echo $cat_count; ?></h3>
                <p>Categories</p>
            </div>
            <div class="icon"><i class="flaticon-menu"></i></div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stat-card">
            <div class="info">
                <h3>₹<?php echo number_format($total_price_inventory, 2); ?></h3>
                <p>Inventory Value</p>
            </div>
            <div class="icon"><i class="flaticon-money"></i></div>
        </div>
    </div>
</div>

<div class="admin-table-wrapper mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Recent Products</h4>
        <a href="products.php" class="btn-admin btn-outline">View All</a>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 5";
            $res = $conn->query($sql);
            if($res->num_rows > 0){
                while($row = $res->fetch_assoc()){
                    echo "<tr>";
                    echo "<td><img src='../" . $row['image'] . "' alt='prod'></td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td><span class='badge badge-light border'>" . htmlspecialchars($row['category']) . "</span></td>";
                    echo "<td>₹" . $row['price'] . "</td>";
                    echo "<td>
                            <a href='edit_product.php?id=".$row['id']."' class='action-btn btn-edit'><i class='flaticon-edit'></i></a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No products found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
