<?php
include '../config/db_connect.php';
include 'includes/header.php';

$error = "";
$success = "";
$product = null;

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='products.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount_price = $_POST['discount_price'];
    $category = $_POST['category'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Check if new image uploaded
    if(isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])){
        $target_dir = "../assets/img/products/";
        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $fileName;
        $db_image_path = "assets/img/products/" . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update with image
            $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=?, discount_price=?, category=?, image=?, is_featured=? WHERE id=?");
            $stmt->bind_param("ssddssii", $title, $description, $price, $discount_price, $category, $db_image_path, $is_featured, $id);
        }
    } else {
        // Update without image
        $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=?, discount_price=?, category=?, is_featured=? WHERE id=?");
        $stmt->bind_param("ssddsii", $title, $description, $price, $discount_price, $category, $is_featured, $id);
    }
    
    if (isset($stmt) && $stmt->execute()) {
        $success = "Product updated successfully!";
        // Refresh product data
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
    } else {
        $error = "Database Error: " . $conn->error;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-table-wrapper p-4">
            <h2 class="mb-4">Edit Product</h2>
            
            <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label class="form-label">Product Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Discount Price (₹)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" value="<?php echo $product['discount_price']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control" required>
                                <option value="Woman" <?php echo $product['category'] == 'Woman' ? 'selected' : ''; ?>>Woman</option>
                                <option value="Man" <?php echo $product['category'] == 'Man' ? 'selected' : ''; ?>>Man</option>
                                <option value="Kids" <?php echo $product['category'] == 'Kids' ? 'selected' : ''; ?>>Kids</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group mb-3 pt-4">
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="flexCheckDefault" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Featured Product?
                                </label>
                            </div>
                         </div>
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label class="form-label">Product Image</label>
                    <div class="custom-file">
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div class="mt-3">
                        <p class="mb-1 text-muted">Current Image:</p>
                        <img src="../<?php echo $product['image']; ?>" style="max-width: 100px; max-height: 100px; border-radius: 6px; margin-right: 15px;">
                        <img id="imgPreview" style="max-width: 200px; max-height: 200px; display: none; border-radius: 8px; border: 1px solid #ddd;" />
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn-admin btn-primary px-4">Update Product</button>
                    <a href="products.php" class="btn-admin btn-outline ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'inline-block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'includes/footer.php'; ?>
