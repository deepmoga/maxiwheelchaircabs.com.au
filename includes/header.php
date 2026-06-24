<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config.php';
}

$settings = getAllSettings();
$site_name = $settings['site_name'] ?? 'Maxi Wheelchair Cabs';
$phone_1 = $settings['phone_1'] ?? '';
$phone_2 = $settings['phone_2'] ?? '';
$site_email = $settings['email'] ?? '';
$site_address = $settings['address'] ?? '';
$whatsapp = $settings['whatsapp_number'] ?? '';
$facebook = $settings['facebook_url'] ?? '';
$instagram = $settings['instagram_url'] ?? '';
$twitter = $settings['twitter_url'] ?? '';

$phone_1_raw = preg_replace('/[^0-9+]/', '', $phone_1);
$phone_2_raw = preg_replace('/[^0-9+]/', '', $phone_2);

$base_url = SITE_URL;

$nav_services = getActiveServices();
if (!isset($page_title)) $page_title = $settings['home_meta_title'] ?? $site_name;
if (!isset($meta_description)) $meta_description = $settings['home_meta_description'] ?? '';
if (!isset($meta_keywords)) $meta_keywords = $settings['home_meta_keywords'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($meta_description); ?>">
    <meta name="keywords" content="<?php echo e($meta_keywords); ?>">
    <meta name="author" content="<?php echo e($site_name); ?>">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="<?php echo e($page_title); ?>">
    <meta property="og:description" content="<?php echo e($meta_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e($base_url); ?>">
    <meta property="og:image" content="<?php echo e($base_url); ?>/images/og-image.jpg">

    <title><?php echo e($page_title); ?></title>

    <link rel="icon" type="image/png" href="<?php echo $base_url; ?>/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/style.css">
</head>
<body>

<div class="preloader">
    <div class="preloader-spinner"></div>
</div>

<!-- Top Bar -->
<div class="topbar">
    <div class="container">
        <div class="topbar-left">
            <?php if ($site_email): ?>
            <a href="mailto:<?php echo e($site_email); ?>">
                <i class="fas fa-envelope"></i>
                <?php echo e($site_email); ?>
            </a>
            <?php endif; ?>
            <?php if ($phone_1): ?>
            <a href="tel:<?php echo e($phone_1_raw); ?>">
                <i class="fas fa-phone"></i>
                <?php echo e($phone_1); ?>
            </a>
            <?php endif; ?>
            <span><i class="fas fa-clock"></i> Available 24/7</span>
        </div>
        <div class="topbar-right">
            <?php if ($facebook): ?><a href="<?php echo e($facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
            <?php if ($instagram): ?><a href="<?php echo e($instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
            <?php if ($whatsapp): ?><a href="https://wa.me/<?php echo e($whatsapp); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a><?php endif; ?>
            <?php if ($twitter): ?><a href="<?php echo e($twitter); ?>" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a><?php endif; ?>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header" id="header">
    <div class="container">
        <a href="<?php echo $base_url; ?>/" class="logo">
            <img src="<?php echo $base_url; ?>/images/logo.png" alt="<?php echo e($site_name); ?>" onerror="this.style.display='none'">
            
        </a>

        <ul class="nav-menu">
            <li><a href="<?php echo $base_url; ?>/" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="<?php echo $base_url; ?>/about" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
            <li class="has-dropdown">
                <a href="<?php echo $base_url; ?>/services" class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['services.php', 'service.php']) ? 'active' : ''; ?>">Services <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                <ul class="dropdown-menu">
                    <?php foreach ($nav_services as $ns): ?>
                    <li><a href="<?php echo $base_url; ?>/service/<?php echo e($ns['slug']); ?>"><i class="<?php echo e($ns['icon']); ?>"></i> <?php echo e($ns['title']); ?></a></li>
                    <?php endforeach; ?>
                    <li class="dropdown-divider"></li>
                    <li><a href="<?php echo $base_url; ?>/services"><i class="fas fa-th-list"></i> All Services</a></li>
                </ul>
            </li>
            <li><a href="<?php echo $base_url; ?>/#why-choose">Why Us</a></li>
            <li><a href="<?php echo $base_url; ?>/#testimonials">Reviews</a></li>
            <li><a href="<?php echo $base_url; ?>/contact" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
        </ul>

        <div class="header-cta">
            <?php if ($phone_1): ?>
            <a href="tel:<?php echo e($phone_1_raw); ?>" class="header-phone">
                <i class="fas fa-phone"></i>
                <span><?php echo e($phone_1); ?></span>
            </a>
            <?php endif; ?>
            <a href="<?php echo $base_url; ?>/contact" class="btn btn-primary">Book Now</a>
        </div>

        <div class="nav-toggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>

<div class="nav-overlay"></div>
