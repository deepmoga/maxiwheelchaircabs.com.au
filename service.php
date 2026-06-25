<?php
require_once 'config.php';

$slug = $_GET['slug'] ?? '';
$service = getServiceBySlug($slug);

if (!$service) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'Service Not Found';
    include 'includes/header.php';
    echo '<section class="page-banner"><div class="container"><h1>Service Not Found</h1></div></section>';
    echo '<section class="section"><div class="container text-center"><p>The service you are looking for does not exist.</p><a href="' . SITE_URL . '/services" class="btn btn-primary">View All Services</a></div></section>';
    include 'includes/footer.php';
    exit;
}

$page_title = $service['meta_title'] ?: $service['title'] . ' | ' . getSetting('site_name');
$meta_description = $service['meta_description'] ?? '';
$meta_keywords = $service['meta_keywords'] ?? '';

$allServices = getActiveServices();

include 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner">
    <div class="container">
        <h1><?php echo e($service['title']); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo $base_url; ?>/">Home</a>
            <span>/</span>
            <a href="<?php echo $base_url; ?>/services">Services</a>
            <span>/</span>
            <span><?php echo e($service['title']); ?></span>
        </div>
    </div>
</section>

<!-- Service Content -->
<section class="service-single">
    <div class="container">
        <div class="service-main-content" data-aos="fade-right">
            <?php
            $top_image = !empty($service['banner_image']) ? $service['banner_image'] : (!empty($service['image']) ? $service['image'] : '');
            if ($top_image): ?>
            <img src="<?php echo e($top_image); ?>" alt="<?php echo e($service['title']); ?>" style="width:100%;height:350px;object-fit:cover;">
            <?php endif; ?>

            <div class="service-description">
                <?php echo $service['description']; ?>
            </div>

            <div style="margin-top:40px;padding:30px;background:var(--light-bg);border-radius:var(--radius);text-align:center;">
                <h3 style="margin-bottom:15px;">Ready to Book Your <?php echo e($service['title']); ?>?</h3>
                <p style="color:var(--text-light);margin-bottom:20px;">Get in touch with us now for a quick, hassle-free booking.</p>
                <div style="display:flex;gap:15px;justify-content:center;flex-wrap:wrap;">
                    <?php if ($phone_1): ?>
                    <a href="tel:<?php echo e($phone_1_raw); ?>" class="btn btn-primary"><i class="fas fa-phone"></i> <?php echo e($phone_1); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo $base_url; ?>/contact" class="btn btn-secondary"><i class="fas fa-calendar-check"></i> Book Online</a>
                </div>
            </div>
        </div>

        <aside class="service-sidebar" data-aos="fade-left">
            <!-- All Services -->
            <div class="sidebar-widget">
                <h3>Our Services</h3>
                <ul class="sidebar-services">
                    <?php foreach ($allServices as $s): ?>
                    <li>
                        <a href="<?php echo $base_url; ?>/service/<?php echo e($s['slug']); ?>" class="<?php echo $s['slug'] === $slug ? 'active' : ''; ?>">
                            <i class="<?php echo e($s['icon']); ?>"></i>
                            <?php echo e($s['title']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Contact Widget -->
            <div class="sidebar-widget sidebar-contact">
                <h3>Need Help?</h3>
                <p>Call us anytime, day or night. We are here to help you get where you need to go.</p>
                <?php if ($phone_1): ?>
                <a href="tel:<?php echo e($phone_1_raw); ?>" class="sidebar-phone">
                    <i class="fas fa-phone"></i> <?php echo e($phone_1); ?>
                </a>
                <?php endif; ?>
                <?php if ($phone_2): ?>
                <a href="tel:<?php echo e($phone_2_raw); ?>" class="sidebar-phone" style="font-size:18px;">
                    <i class="fas fa-phone"></i> <?php echo e($phone_2); ?>
                </a>
                <?php endif; ?>
                <a href="<?php echo $base_url; ?>/contact" class="btn btn-primary" style="margin-top:10px;">Book Online</a>
            </div>
        </aside>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
