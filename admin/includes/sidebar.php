<aside class="admin-sidebar" id="adminSidebar">
    <a href="../index.php" class="brand">Kashmiri Stitch</a>
    
    <ul class="nav-links">
        <li>
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="flaticon-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                <i class="flaticon-shopping-bag"></i> Products
            </a>
        </li>
        <li>
            <a href="add_product.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>">
                <i class="flaticon-plus"></i> Add Product
            </a>
        </li>
        <li>
            <a href="#">
                <i class="flaticon-user"></i> Users
            </a>
        </li>
        <li>
            <a href="#">
                <i class="flaticon-shopping-cart"></i> Orders
            </a>
        </li>
        <li style="margin-top: 50px;">
            <a href="../logout.php">
                <i class="flaticon-logout"></i> Logout
            </a>
        </li>
    </ul>
</aside>
