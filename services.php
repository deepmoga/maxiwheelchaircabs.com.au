<?php
require_once 'config.php';

$page_title = 'Our Services | ' . getSetting('site_name');
$meta_description = 'Explore our professional taxi services in Perth — wheelchair taxis, airport transfers, baby seat cabs and wedding transport. Book your ride today.';
$meta_keywords = 'taxi services Perth, wheelchair taxi, airport transfer, baby seat taxi, wedding transport, maxi cab Perth';

$services = getActiveServices();
include 'includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Our Services</h1>
        <div class="breadcrumb">
            <a href="<?php echo $base_url; ?>/">Home</a>
            <span>/</span>
            <span>Services</span>
        </div>
    </div>
</section>

<section class="services-page">
    <div class="container">
        <div class="text-center" style="margin-bottom:50px;" data-aos="fade-up">
            <span class="section-subtitle">What We Offer</span>
            <h2 class="section-title">Professional Transport Solutions for Every Need</h2>
            <p class="section-desc">We cover every kind of journey — from accessible wheelchair rides and airport pickups, to family-friendly baby seat taxis and wedding guest transfers.</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $i => $svc): ?>
            <div class="service-card" data-aos="fade-up" data-aos-delay="<?php echo ($i + 1) * 100; ?>">
                <div class="service-card-img">
                    <img src="<?php echo $base_url . '/' . e($svc['image'] ?: 'images/service-default.jpg'); ?>" alt="<?php echo e($svc['title']); ?>">
                    <div class="service-icon"><i class="<?php echo e($svc['icon']); ?>"></i></div>
                </div>
                <div class="service-card-body">
                    <h3><?php echo e($svc['title']); ?></h3>
                    <p><?php echo e($svc['short_description']); ?></p>
                </div>
                <div class="service-card-footer">
                    <a href="<?php echo $base_url; ?>/service/<?php echo e($svc['slug']); ?>" class="svc-btn-learn"><i class="fas fa-arrow-right"></i> Learn More</a>
                    <button type="button" class="svc-btn-book open-booking-modal" data-service="<?php echo e($svc['title']); ?>"><i class="fas fa-calendar-check"></i> Book Now</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
