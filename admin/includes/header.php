<?php
requireLogin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo getSetting('site_name', 'Maxi Wheelchair Cabs'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>Maxi<span>WC</span></h2>
            <p>Admin Panel</p>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="settings.php" class="<?php echo $currentPage === 'settings' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Site Settings</a>
            <a href="homepage.php" class="<?php echo $currentPage === 'homepage' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Homepage</a>
            <a href="services.php" class="<?php echo in_array($currentPage, ['services', 'edit-service']) ? 'active' : ''; ?>"><i class="fas fa-concierge-bell"></i> Services</a>
            <a href="testimonials.php" class="<?php echo in_array($currentPage, ['testimonials', 'edit-testimonial']) ? 'active' : ''; ?>"><i class="fas fa-star"></i> Testimonials</a>
            <a href="pages.php" class="<?php echo in_array($currentPage, ['pages', 'edit-page']) ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Pages</a>
            <a href="inquiries.php" class="<?php echo $currentPage === 'inquiries' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Inquiries</a>
            <a href="seo.php" class="<?php echo $currentPage === 'seo' ? 'active' : ''; ?>"><i class="fas fa-search"></i> SEO Settings</a>
            <a href="mail-settings.php" class="<?php echo $currentPage === 'mail-settings' ? 'active' : ''; ?>"><i class="fas fa-envelope-open"></i> Mail Settings</a>
            <div class="sidebar-divider"></div>
            <a href="../" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="admin-header">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <div class="admin-header-right">
                <span class="admin-user"><i class="fas fa-user-circle"></i> Admin</span>
            </div>
        </header>
        <div class="content-area">
