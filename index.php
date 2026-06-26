<?php
require_once 'config.php';

$page_title = getSetting('home_meta_title', 'Maxi Wheelchair Cabs');
$meta_description = getSetting('home_meta_description', '');
$meta_keywords = getSetting('home_meta_keywords', '');

$services = getActiveServices();
$testimonials = getActiveTestimonials();

include 'includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero" id="home">
    <div class="hero-bg">
        <img src="<?php echo e(getSetting('hero_image', 'images/hero-bg.jpg')); ?>" alt="Wheelchair accessible taxi in Perth">
    </div>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-star"></i> <?php echo e(getSetting('hero_badge', "Perth's Top-Rated Accessible Taxi")); ?>
            </div>
            <h1 class="hero-title">
                <?php echo getSetting('hero_title', 'Professional <span>Wheelchair Taxi</span> & Maxi Cab Service'); ?>
            </h1>
            <p class="hero-desc">
                <?php echo e(getSetting('hero_description', 'Safe, comfortable and on-time transport you can always count on.')); ?>
            </p>
            <div class="hero-btns">
                <a href="tel:<?php echo e($phone_1_raw); ?>" class="btn btn-primary">
                    <i class="fas fa-phone"></i> Call Now
                </a>
                <a href="<?php echo $base_url; ?>/services" class="btn btn-outline">
                    <i class="fas fa-arrow-right"></i> Our Services
                </a>
            </div>
            <div class="hero-features">
                <div class="hero-feature"><i class="fas fa-check-circle"></i><span>24/7 Available</span></div>
                <div class="hero-feature"><i class="fas fa-check-circle"></i><span>No Hidden Fees</span></div>
                <div class="hero-feature"><i class="fas fa-check-circle"></i><span>Licensed Drivers</span></div>
            </div>
        </div>

        <!-- Hero Booking Form -->
        <div class="hero-form-wrap">
            <div class="hero-form">
                <div class="hero-form-header">
                    <i class="fas fa-taxi"></i>
                    <h3>Book Your Ride Now</h3>
                    <p>Quick, easy and free to request</p>
                </div>
                <form id="heroBookingForm">
                    <div class="hf-row">
                        <div class="hf-group">
                            <label><i class="fas fa-user"></i> Name *</label>
                            <input type="text" name="name" required placeholder="Your full name">
                        </div>
                        <div class="hf-group">
                            <label><i class="fas fa-phone"></i> Phone *</label>
                            <input type="tel" name="phone" required placeholder="Phone number">
                        </div>
                    </div>
                    <div class="hf-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" placeholder="Email address">
                    </div>
                    <div class="hf-row">
                        <div class="hf-group">
                            <label><i class="fas fa-map-marker-alt"></i> From *</label>
                            <input type="text" name="pickup_location" required placeholder="Pickup location">
                        </div>
                        <div class="hf-group">
                            <label><i class="fas fa-flag-checkered"></i> To *</label>
                            <input type="text" name="dropoff_location" required placeholder="Drop-off location">
                        </div>
                    </div>
                    <div class="hf-row">
                        <div class="hf-group">
                            <label><i class="fas fa-calendar"></i> Date *</label>
                            <input type="date" name="travel_date" required>
                        </div>
                        <div class="hf-group">
                            <label><i class="fas fa-clock"></i> Time *</label>
                            <input type="time" name="travel_time" required>
                        </div>
                    </div>
                    <div class="hf-group">
                        <label><i class="fas fa-comment-dots"></i> Message</label>
                        <textarea name="message" rows="2" placeholder="Special requirements or notes..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary hf-submit" id="heroFormBtn">
                        <i class="fas fa-paper-plane"></i> Request a Ride
                    </button>
                </form>
                <div id="heroFormMsg" class="hf-message" style="display:none;"></div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<section class="section about" id="about">
    <div class="container">
        <div class="about-images" data-aos="fade-right">
            <div class="about-img-main">
                <img src="<?php echo e(getSetting('about_image', 'images/about-main.jpg')); ?>" alt="About Maxi Wheelchair Cabs Perth">
            </div>
            <div class="about-img-secondary">
                <img src="<?php echo e(getSetting('about_image_small', 'images/about-small.jpg')); ?>" alt="Accessible taxi service">
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
                <?php echo getSetting('about_description', '<p>We are a dedicated team of professional drivers.</p>'); ?>
            </div>
            <ul class="about-list">
                <li><i class="fas fa-check-circle"></i> Fully equipped wheelchair accessible vehicles</li>
                <li><i class="fas fa-check-circle"></i> Professional, trained and friendly drivers</li>
                <li><i class="fas fa-check-circle"></i> Transparent pricing with no surprise charges</li>
                <li><i class="fas fa-check-circle"></i> Serving all Perth suburbs day and night</li>
                <li><i class="fas fa-check-circle"></i> Easy online and phone booking options</li>
            </ul>
            <a href="<?php echo $base_url; ?>/about" class="btn btn-primary">Read More <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="section services" id="services">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="section-subtitle">What We Offer</span>
            <h2 class="section-title">Our Professional Transport Services</h2>
            <p class="section-desc">From accessible wheelchair taxis to comfortable airport pickups, we provide a complete range of transport solutions tailored to your needs across Perth and surrounding areas.</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $i => $svc): ?>
            <div class="service-card" data-aos="fade-up" data-aos-delay="<?php echo ($i + 1) * 100; ?>">
                <div class="service-card-img">
                    <img src="<?php echo $base_url . '/' . e($svc['image'] ?: 'images/service-default.jpg'); ?>" alt="<?php echo e($svc['title']); ?> Perth">
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

<!-- WHY CHOOSE US -->
<?php
$featureIcons = ['fas fa-hand-holding-dollar', 'fas fa-user-shield', 'fas fa-clock', 'fas fa-comment-sms', 'fas fa-car-side', 'fas fa-credit-card'];
$defaultFeatures = [
    ['No Hidden Charges', 'What we quote is what you pay. Transparent metered or fixed fares, always.'],
    ['Professional Drivers', 'Trained, licensed and background-checked for your complete safety.'],
    ['24/7 Availability', 'Early morning flight or late night ride, we are always just a call away.'],
    ['Driver SMS Alerts', 'Get a text when your driver is 10 minutes away. No guessing, no waiting.'],
    ['Clean Modern Fleet', 'Well-maintained vehicles from sedans to 13-seater maxi cabs ready to go.'],
    ['All Payment Methods', 'Cash, card, EFTPOS — pay however works best for you.'],
];
?>
<section class="section why-choose" id="why-choose">
    <div class="container">
        <div class="why-content">
            <span class="section-subtitle" data-aos="fade-right">Why Choose Us</span>
            <h2 class="section-title" data-aos="fade-right" data-aos-delay="100"><?php echo e($settings['whychoose_title'] ?? 'Honest Pricing, Experienced Drivers & Reliable Service Every Time'); ?></h2>
            <p class="about-text" data-aos="fade-right" data-aos-delay="150"><?php echo e($settings['whychoose_description'] ?? 'We are not just another taxi company. We are Perth locals who understand your travel needs and deliver a level of care that bigger operators simply cannot match.'); ?></p>
            <div class="features-list" data-aos="fade-up" data-aos-delay="200">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="feature-item">
                    <div class="feature-icon"><i class="<?php echo $featureIcons[$i-1]; ?>"></i></div>
                    <div class="feature-text">
                        <h4><?php echo e($settings['feature_' . $i . '_title'] ?? $defaultFeatures[$i-1][0]); ?></h4>
                        <p><?php echo e($settings['feature_' . $i . '_desc'] ?? $defaultFeatures[$i-1][1]); ?></p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="why-image" data-aos="fade-left">
            <img src="<?php echo e($settings['whychoose_image'] ?? 'images/why-choose.jpg'); ?>" alt="Best rated taxi company Perth">
            <div class="why-stats-card">
                <div class="stat-item"><div class="stat-number">4.9</div><div class="stat-label">Rating</div></div>
                <div class="stat-item"><div class="stat-number">10K+</div><div class="stat-label">Rides</div></div>
                <div class="stat-item"><div class="stat-number">24/7</div><div class="stat-label">Service</div></div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section how-it-works">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="section-subtitle">Easy Booking</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-desc">Getting a ride with us is quick and straightforward. Just four simple steps and you are on your way.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card" data-aos="fade-up" data-aos-delay="100">
                <div class="step-number">1</div>
                <div class="step-icon"><i class="fas fa-phone-volume"></i></div>
                <h3>Call or Book Online</h3>
                <p>Ring us directly or use our easy online booking form to request your ride.</p>
            </div>
            <div class="step-card" data-aos="fade-up" data-aos-delay="200">
                <div class="step-number">2</div>
                <div class="step-icon"><i class="fas fa-location-dot"></i></div>
                <h3>Share Your Details</h3>
                <p>Tell us your pickup location, destination, date and any special requirements.</p>
            </div>
            <div class="step-card" data-aos="fade-up" data-aos-delay="300">
                <div class="step-number">3</div>
                <div class="step-icon"><i class="fas fa-car"></i></div>
                <h3>Driver On the Way</h3>
                <p>We confirm your booking instantly and send a driver alert when they are close.</p>
            </div>
            <div class="step-card" data-aos="fade-up" data-aos-delay="400">
                <div class="step-number">4</div>
                <div class="step-icon"><i class="fas fa-face-smile"></i></div>
                <h3>Enjoy Your Ride</h3>
                <p>Sit back, relax and enjoy a smooth, comfortable trip to your destination.</p>
            </div>
        </div>
    </div>
</section>

<!-- STATS COUNTER -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="100">
                <i class="fas fa-smile"></i>
                <div class="counter" data-target="5000" data-suffix="+">0</div>
                <div class="counter-label">Happy Passengers</div>
            </div>
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="200">
                <i class="fas fa-car"></i>
                <div class="counter" data-target="15000" data-suffix="+">0</div>
                <div class="counter-label">Rides Completed</div>
            </div>
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="300">
                <i class="fas fa-map-marker-alt"></i>
                <div class="counter" data-target="300" data-suffix="+">0</div>
                <div class="counter-label">Suburbs Covered</div>
            </div>
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="400">
                <i class="fas fa-award"></i>
                <div class="counter" data-target="10" data-suffix="+">0</div>
                <div class="counter-label">Years Experience</div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section testimonials" id="testimonials">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="section-subtitle">Customer Feedback</span>
            <h2 class="section-title">What Our Passengers Are Saying</h2>
            <p class="section-desc">Real stories from real people who trust us with their most important journeys.</p>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $i => $t): ?>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="<?php echo ($i + 1) * 100; ?>">
                <i class="fas fa-quote-right quote-icon"></i>
                <div class="testimonial-stars">
                    <?php for ($s = 0; $s < $t['rating']; $s++): ?><i class="fas fa-star"></i><?php endfor; ?>
                </div>
                <p class="testimonial-text">"<?php echo e($t['review']); ?>"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar"><?php
                        $initials = '';
                        $parts = explode(' ', $t['name']);
                        foreach ($parts as $p) $initials .= strtoupper(substr($p, 0, 1));
                        echo e($initials);
                    ?></div>
                    <div class="testimonial-info">
                        <h4><?php echo e($t['name']); ?></h4>
                        <p><?php echo e($t['location']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
