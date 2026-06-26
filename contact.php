<?php
require_once 'config.php';

$page_title = 'Contact Us | ' . getSetting('site_name');
$meta_description = 'Get in touch with Maxi Wheelchair Cabs Perth. Book a taxi, request a quote or ask a question. Available 24/7 by phone, email or online form.';
$meta_keywords = 'contact taxi Perth, book taxi Perth, wheelchair cab booking, taxi phone number Perth';

$services = getActiveServices();

include 'includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="breadcrumb">
            <a href="<?php echo $base_url; ?>/">Home</a>
            <span>/</span>
            <span>Contact</span>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div data-aos="fade-right">
                <span class="section-subtitle">Get in Touch</span>
                <h2 class="section-title" style="text-align:left;">We Are Here to Help You</h2>
                <p class="about-text">Have a question or ready to book your ride? Reach out to us by phone, email or simply fill in the form. We respond fast and we are always happy to help.</p>

                <div class="contact-info-cards">
                    <?php if ($phone_1): ?>
                    <div class="contact-card">
                        <i class="fas fa-phone"></i>
                        <h4>Phone 1</h4>
                        <a href="tel:<?php echo e($phone_1_raw); ?>"><?php echo e($phone_1); ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if ($phone_2): ?>
                    <div class="contact-card">
                        <i class="fas fa-mobile-screen"></i>
                        <h4>Phone 2</h4>
                        <a href="tel:<?php echo e($phone_2_raw); ?>"><?php echo e($phone_2); ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if ($site_email): ?>
                    <div class="contact-card">
                        <i class="fas fa-envelope"></i>
                        <h4>Email</h4>
                        <a href="mailto:<?php echo e($site_email); ?>"><?php echo e($site_email); ?></a>
                    </div>
                    <?php endif; ?>
                    <div class="contact-card">
                        <i class="fas fa-clock"></i>
                        <h4>Working Hours</h4>
                        <p>24/7 — All Year Round</p>
                    </div>
                </div>

                <?php if ($site_address): ?>
                <div class="contact-card" style="margin-bottom:20px;">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Address</h4>
                    <p><?php echo e($site_address); ?></p>
                </div>
                <?php endif; ?>

                <div class="contact-map">
                    <?php echo getSetting('map_embed', ''); ?>
                </div>
            </div>

            <!-- Booking Form (same style as hero) -->
            <div data-aos="fade-left">
                <div class="hero-form" style="border-radius:16px;box-shadow:var(--shadow);">
                    <div class="hero-form-header">
                        <i class="fas fa-taxi"></i>
                        <h3>Book Your Ride</h3>
                        <p>Fill in the details and we will get back to you</p>
                    </div>
                    <form id="contactBookingForm">
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
                            <textarea name="message" rows="3" placeholder="Special requirements or notes..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary hf-submit" id="contactFormBtn">
                            <i class="fas fa-paper-plane"></i> Submit Booking Request
                        </button>
                    </form>
                    <div id="contactFormMsg" class="hf-message" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('contactBookingForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('contactFormBtn');
            var msgDiv = document.getElementById('contactFormMsg');
            var orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            msgDiv.style.display = 'none';

            fetch('ajax-booking.php', { method: 'POST', body: new FormData(form) })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                msgDiv.style.display = 'block';
                if (data.success) {
                    msgDiv.className = 'hf-message success';
                    msgDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    form.reset();
                    form.style.display = 'none';
                } else {
                    msgDiv.className = 'hf-message error';
                    msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + data.message;
                }
                btn.disabled = false;
                btn.innerHTML = orig;
            })
            .catch(function() {
                msgDiv.style.display = 'block';
                msgDiv.className = 'hf-message error';
                msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Something went wrong. Please call us directly.';
                btn.disabled = false;
                btn.innerHTML = orig;
            });
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
