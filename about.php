<?php
require_once 'config.php';

$page_title = 'About Us | ' . getSetting('site_name');
$meta_description = 'Learn about Maxi Wheelchair Cabs Perth — over 10 years of providing professional, accessible taxi services across Perth, WA. Meet our team.';
$meta_keywords = 'about maxi wheelchair cabs, Perth taxi company, accessible transport Perth, wheelchair taxi company';

include 'includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>About Us</h1>
        <div class="breadcrumb">
            <a href="<?php echo $base_url; ?>/">Home</a>
            <span>/</span>
            <span>About Us</span>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="section about" id="about">
    <div class="container">
        <div class="about-images" data-aos="fade-right">
            <div class="about-img-main">
                <img src="<?php echo e(getSetting('about_image', 'images/about-main.jpg')); ?>" alt="About Maxi Wheelchair Cabs">
            </div>
            <div class="about-img-secondary">
                <img src="<?php echo e(getSetting('about_image_small', 'images/about-small.jpg')); ?>" alt="Our team">
            </div>
            <div class="about-experience">
                <div class="number">10+</div>
                <div class="label">Years of<br>Experience</div>
            </div>
        </div>
        <div class="about-content" data-aos="fade-left">
            <span class="section-subtitle"><?php echo e(getSetting('about_subtitle', 'Welcome to Maxi Wheelchair Cabs')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('about_title', "Perth's Most Trusted Accessible Taxi Service")); ?></h2>
            <div class="about-text">
                <?php echo getSetting('about_description', ''); ?>
            </div>
            <ul class="about-list">
                <li><i class="fas fa-check-circle"></i> Fully equipped wheelchair accessible vehicles</li>
                <li><i class="fas fa-check-circle"></i> Professional, trained and friendly drivers</li>
                <li><i class="fas fa-check-circle"></i> Transparent pricing with no surprise charges</li>
                <li><i class="fas fa-check-circle"></i> Serving all Perth suburbs day and night</li>
                <li><i class="fas fa-check-circle"></i> Easy online and phone booking options</li>
            </ul>
            <a href="<?php echo $base_url; ?>/contact" class="btn btn-primary">Contact Us <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-box" data-aos="zoom-in"><i class="fas fa-smile"></i><div class="counter" data-target="5000" data-suffix="+">0</div><div class="counter-label">Happy Passengers</div></div>
            <div class="stat-box" data-aos="zoom-in"><i class="fas fa-car"></i><div class="counter" data-target="15000" data-suffix="+">0</div><div class="counter-label">Rides Completed</div></div>
            <div class="stat-box" data-aos="zoom-in"><i class="fas fa-map-marker-alt"></i><div class="counter" data-target="300" data-suffix="+">0</div><div class="counter-label">Suburbs Covered</div></div>
            <div class="stat-box" data-aos="zoom-in"><i class="fas fa-award"></i><div class="counter" data-target="10" data-suffix="+">0</div><div class="counter-label">Years Experience</div></div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
