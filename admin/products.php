<?php 
include '../config/db_connect.php';
include 'includes/header.php'; 

// Check if delete requested
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    echo "<script>window.location.href='products.php';</script>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Products</h2>
    <a href="add_product.php" class="btn-admin btn-primary"><i class="flaticon-plus"></i> Add New Product</a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $res = $conn->query($sql);
            if($res->num_rows > 0){
                while($row = $res->fetch_assoc()){
                    $featuredBadge = $row['is_featured'] ? "<span class='badge badge-success'>Yes</span>" : "<span class='badge badge-secondary'>No</span>";
                    
                    echo "<tr>";
                    echo "<td>#" . $row['id'] . "</td>";
                    echo "<td><img src='../" . $row['image'] . "' alt='prod'></td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>₹" . $row['price'] . "</td>";
                    echo "<td>" . $featuredBadge . "</td>";
                    echo "<td>
                            <a href='edit_product.php?id=".$row['id']."' class='action-btn btn-edit'><i class='flaticon-edit'></i></a>
                            <a href='products.php?delete=".$row['id']."' onclick='return confirm(\"Are you sure?\")' class='action-btn btn-delete'><i class='flaticon-close'></i></a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No products found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
