<!-- CTA Section 1 -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2><?php echo $settings['cta_title'] ?? 'Ready to Book Your Next Ride?'; ?></h2>
            <p><?php echo $settings['cta_description'] ?? 'Call us now for fast, friendly and affordable taxi service across Perth.'; ?></p>
        </div>
        <div class="cta-actions">
            <?php if ($phone_1): ?>
            <a href="tel:<?php echo e($phone_1_raw); ?>" class="cta-phone">
                <i class="fas fa-phone-volume"></i>
                <?php echo e($phone_1); ?>
            </a>
            <?php endif; ?>
            <a href="<?php echo $base_url; ?>/contact" class="btn btn-secondary">Book Online</a>
        </div>
    </div>
</section>

<!-- CTA Section 2 -->
<section class="cta-section-2">
    <div class="container">
        <div class="cta2-inner" data-aos="fade-up">
            <div class="cta2-content">
                <i class="fas fa-taxi cta2-icon"></i>
                <h2><?php echo $settings['cta2_title'] ?? 'Need a Wheelchair Accessible Taxi Right Now?'; ?></h2>
                <p><?php echo $settings['cta2_description'] ?? 'Do not wait around. Our friendly team is standing by to get you where you need to go — safely, comfortably and on time.'; ?></p>
                <div class="cta2-btns">
                    <?php if ($phone_1): ?>
                    <a href="tel:<?php echo e($phone_1_raw); ?>" class="btn btn-primary"><i class="fas fa-phone"></i> Call <?php echo e($phone_1); ?></a>
                    <?php endif; ?>
                    <?php if ($phone_2): ?>
                    <a href="tel:<?php echo e($phone_2_raw); ?>" class="btn btn-outline-dark"><i class="fas fa-phone"></i> <?php echo e($phone_2); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer" id="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <div class="logo-text">Maxi<span>Wheelchair</span> Cabs</div>
                <p>Perth's most trusted wheelchair accessible taxi and maxi cab service. We take pride in providing safe, comfortable and reliable transport for everyone, every day of the year.</p>
                <div class="footer-social">
                    <?php if ($facebook): ?><a href="<?php echo e($facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if ($instagram): ?><a href="<?php echo e($instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if ($whatsapp): ?><a href="https://wa.me/<?php echo e($whatsapp); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a><?php endif; ?>
                    <?php if ($twitter): ?><a href="<?php echo e($twitter); ?>" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a><?php endif; ?>
                </div>
            </div>

            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo $base_url; ?>/">Home</a></li>
                    <li><a href="<?php echo $base_url; ?>/about">About Us</a></li>
                    <li><a href="<?php echo $base_url; ?>/services">Our Services</a></li>
                    <li><a href="<?php echo $base_url; ?>/contact">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Our Services</h3>
                <ul class="footer-links">
                    <?php
                    $footerServices = getActiveServices();
                    foreach ($footerServices as $fs): ?>
                    <li><a href="<?php echo $base_url; ?>/service/<?php echo e($fs['slug']); ?>"><?php echo e($fs['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Get in Touch</h3>
                <ul class="footer-contact">
                    <?php if ($site_address): ?>
                    <li><i class="fas fa-map-marker-alt"></i><span><?php echo e($site_address); ?></span></li>
                    <?php endif; ?>
                    <?php if ($phone_1): ?>
                    <li><i class="fas fa-phone"></i><a href="tel:<?php echo e($phone_1_raw); ?>"><?php echo e($phone_1); ?></a></li>
                    <?php endif; ?>
                    <?php if ($phone_2): ?>
                    <li><i class="fas fa-phone"></i><a href="tel:<?php echo e($phone_2_raw); ?>"><?php echo e($phone_2); ?></a></li>
                    <?php endif; ?>
                    <?php if ($site_email): ?>
                    <li><i class="fas fa-envelope"></i><a href="mailto:<?php echo e($site_email); ?>"><?php echo e($site_email); ?></a></li>
                    <?php endif; ?>
                    <li><i class="fas fa-clock"></i><span>24 Hours / 7 Days a Week</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo e($site_name); ?>. All Rights Reserved.</p>
            <div class="footer-bottom-links">
                <a href="<?php echo $base_url; ?>/privacy-policy">Privacy Policy</a>
                <a href="<?php echo $base_url; ?>/terms-conditions">Terms & Conditions</a>
            </div>
        </div>
        <div style="text-align:center;padding:12px 0 5px;font-size:12px;color:rgba(255,255,255,0.4);">
            Made with <span style="color:#e74c3c;">&hearts;</span> by <a href="https://officialdigitalmarketing.in/" target="_blank" rel="noopener" style="color:var(--primary);font-weight:600;">Official Digital Marketing</a>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<?php if ($whatsapp): ?>
<a href="https://wa.me/<?php echo e($whatsapp); ?>?text=Hi%2C%20I%20would%20like%20to%20book%20a%20taxi." class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Scroll to Top -->
<button class="scroll-top" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Booking Modal -->
<div class="booking-modal-overlay" id="bookingModal">
    <div class="booking-modal">
        <button class="booking-modal-close" id="closeBookingModal"><i class="fas fa-times"></i></button>
        <div class="hero-form">
            <div class="hero-form-header">
                <i class="fas fa-taxi"></i>
                <h3>Book Your Ride Now</h3>
                <p>Quick, easy and free to request</p>
            </div>
            <form id="modalBookingForm">
                <input type="hidden" name="service" id="modalServiceField" value="">
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
                <button type="submit" class="btn btn-primary hf-submit" id="modalFormBtn">
                    <i class="fas fa-paper-plane"></i> Request a Ride
                </button>
            </form>
            <div id="modalFormMsg" class="hf-message" style="display:none;"></div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init({duration:800,easing:'ease-in-out',once:true,offset:100});</script>
<script src="<?php echo $base_url; ?>/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
