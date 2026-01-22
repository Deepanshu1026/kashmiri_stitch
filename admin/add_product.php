<?php
include '../config/db_connect.php';
include 'includes/header.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount_price = $_POST['discount_price'];
    $category = $_POST['category']; // Man, Woman, Kids
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle Image Upload
    $target_dir = "../assets/img/products/";
    // Ensure directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Use timestamp to prevent filename collision
    $fileName = time() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $fileName;
    $db_image_path = "assets/img/products/" . $fileName; // Path to save in DB

    $uploadOk = 1;
    // Check if image file is a actual image or fake image
    if(isset($_FILES["image"]["tmp_name"]) && !empty($_FILES["image"]["tmp_name"])){
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    } else {
        $error = "Please select an image.";
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Prepare stmt
        $stmt = $conn->prepare("INSERT INTO products (title, description, price, discount_price, category, image, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddssi", $title, $description, $price, $discount_price, $category, $db_image_path, $is_featured);
        
        if ($stmt->execute()) {
            $success = "Product uploaded successfully!";
        } else {
            $error = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        if(!$error) $error = "Sorry, there was an error uploading your file.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-table-wrapper p-4">
            <h2 class="mb-4">Add New Product</h2>
            
            <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label class="form-label">Product Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Discount Price (₹)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control" required>
                                <option value="Woman">Woman</option>
                                <option value="Man">Man</option>
                                <option value="Kids">Kids</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group mb-3 pt-4">
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="flexCheckDefault">
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
                        <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(this)">
                    </div>
                    <div class="mt-3">
                        <img id="imgPreview" style="max-width: 200px; max-height: 200px; display: none; border-radius: 8px; border: 1px solid #ddd;" />
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn-admin btn-primary px-4">Upload Product</button>
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
            document.getElementById('imgPreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'includes/footer.php'; ?>
