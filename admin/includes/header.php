<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ideally check for admin role here
// if(!isset($_SESSION['admin_logged_in'])) { header('Location: ../login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kashmiri Stitch - Admin Dashboard</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/icon/flaticon_glamer.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Sidebar Overlay -->
    <div class="overlay" id="sidebarOverlay"></div>

    <?php include 'sidebar.php'; ?>

    <!-- Header -->
    <header class="admin-header">
        <div class="d-flex align-items-center">
            <span class="toggle-sidebar mr-4" id="sidebarToggle"><i class="flaticon-menu"></i></span>
            <h4 class="mb-0 d-none d-sm-block">Dashboard</h4>
        </div>
        
        <div class="user-profile">
            <div class="text-right d-none d-sm-block">
                <span class="d-block font-weight-bold" style="font-size: 14px;">Administrator</span>
                <span class="d-block text-muted" style="font-size: 12px;">admin@kashmiristitch.com</span>
            </div>
            <img src="../assets/img/review-author-1.png" alt="Admin">
        </div>
    </header>

    <div class="admin-main">
