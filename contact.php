<?php
require_once 'config.php';

$page_title = 'Contact Us | ' . getSetting('site_name');
$meta_description = 'Get in touch with Maxi Wheelchair Cabs Perth. Book a taxi, request a quote or ask a question. Available 24/7 by phone, email or online form.';
$meta_keywords = 'contact taxi Perth, book taxi Perth, wheelchair cab booking, taxi phone number Perth';

$services = getActiveServices();

$form_success = '';
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inquiry'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $pickup = trim($_POST['pickup_location'] ?? '');
    $dropoff = trim($_POST['dropoff_location'] ?? '');
    $travel_date = trim($_POST['travel_date'] ?? '');
    $passengers = trim($_POST['passengers'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($phone)) {
        $form_error = 'Please fill in your name and phone number.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, service, pickup_location, dropoff_location, travel_date, passengers, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $service, $pickup, $dropoff, $travel_date, $passengers, $message]);

            // Send email
            $emailSent = false;
            try {
                require_once 'phpmailer/autoload.php';
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                $smtp_host = getSetting('smtp_host', 'smtp.gmail.com');
                $smtp_port = getSetting('smtp_port', '587');
                $smtp_user = getSetting('smtp_username', '');
                $smtp_pass = getSetting('smtp_password', '');
                $from_email = getSetting('smtp_from_email', '');
                $from_name = getSetting('smtp_from_name', 'Maxi Wheelchair Cabs');
                $admin_email = getSetting('admin_email', '');

                $mail->isSMTP();
                $mail->Host = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_user;
                $mail->Password = $smtp_pass;
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = (int)$smtp_port;

                $mail->setFrom($from_email, $from_name);
                $mail->addAddress($admin_email);
                $mail->addReplyTo($email ?: $from_email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'New Booking Inquiry from ' . $name;

                $mail->Body = getEmailTemplate($name, $email, $phone, $service, $pickup, $dropoff, $travel_date, $passengers, $message);
                $mail->AltBody = "New inquiry from $name\nPhone: $phone\nEmail: $email\nService: $service\nPickup: $pickup\nDropoff: $dropoff\nDate: $travel_date\nPassengers: $passengers\nMessage: $message";

                $mail->send();

                // Send confirmation to customer
                if ($email) {
                    $mail->clearAddresses();
                    $mail->addAddress($email, $name);
                    $mail->Subject = 'Booking Inquiry Received - ' . $from_name;
                    $mail->Body = getCustomerEmailTemplate($name, $service, $from_name, $phone_1);
                    $mail->AltBody = "Hi $name, thank you for contacting $from_name. We have received your inquiry and will get back to you shortly.";
                    $mail->send();
                }

                $emailSent = true;
            } catch (Exception $e) {
                // Email failed but inquiry saved to database
            }

            $form_success = 'Thank you! Your booking inquiry has been submitted successfully. We will get back to you shortly.';
            $name = $email = $phone = $service = $pickup = $dropoff = $travel_date = $passengers = $message = '';

        } catch (PDOException $e) {
            $form_error = 'Something went wrong. Please try again or call us directly.';
        }
    }
}

function getEmailTemplate($name, $email, $phone, $service, $pickup, $dropoff, $date, $passengers, $message) {
    return '<!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <tr><td style="background:#1a1a2e;padding:30px 40px;text-align:center;">
            <h1 style="color:#FFC107;margin:0;font-size:22px;">Maxi Wheelchair Cabs</h1>
            <p style="color:rgba(255,255,255,0.7);margin:5px 0 0;font-size:13px;">New Booking Inquiry</p>
        </td></tr>
        <tr><td style="padding:35px 40px;">
            <h2 style="color:#1a1a2e;margin:0 0 20px;font-size:18px;">New Inquiry from ' . htmlspecialchars($name) . '</h2>
            <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;color:#333;">
                <tr style="background:#f8f9fa;"><td style="font-weight:bold;width:140px;padding:12px;border-bottom:1px solid #eee;">Name</td><td style="padding:12px;border-bottom:1px solid #eee;">' . htmlspecialchars($name) . '</td></tr>
                <tr><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Phone</td><td style="padding:12px;border-bottom:1px solid #eee;"><a href="tel:' . htmlspecialchars($phone) . '" style="color:#1a1a2e;font-weight:bold;">' . htmlspecialchars($phone) . '</a></td></tr>
                <tr style="background:#f8f9fa;"><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Email</td><td style="padding:12px;border-bottom:1px solid #eee;">' . htmlspecialchars($email ?: 'Not provided') . '</td></tr>
                <tr><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Service</td><td style="padding:12px;border-bottom:1px solid #eee;"><span style="background:#FFC107;color:#1a1a2e;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:bold;">' . htmlspecialchars($service ?: 'General') . '</span></td></tr>
                <tr style="background:#f8f9fa;"><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Pickup</td><td style="padding:12px;border-bottom:1px solid #eee;">' . htmlspecialchars($pickup ?: 'Not specified') . '</td></tr>
                <tr><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Drop-off</td><td style="padding:12px;border-bottom:1px solid #eee;">' . htmlspecialchars($dropoff ?: 'Not specified') . '</td></tr>
                <tr style="background:#f8f9fa;"><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Travel Date</td><td style="padding:12px;border-bottom:1px solid #eee;">' . htmlspecialchars($date ?: 'Not specified') . '</td></tr>
                <tr><td style="font-weight:bold;padding:12px;border-bottom:1px solid #eee;">Passengers</td><td style="padding:12px;border-bottom:1px solid #eee;">' . htmlspecialchars($passengers ?: 'Not specified') . '</td></tr>
            </table>
            ' . ($message ? '<div style="margin-top:20px;padding:15px;background:#f8f9fa;border-radius:8px;border-left:4px solid #FFC107;"><strong style="color:#1a1a2e;">Message:</strong><p style="margin:8px 0 0;color:#555;line-height:1.6;">' . nl2br(htmlspecialchars($message)) . '</p></div>' : '') . '
        </td></tr>
        <tr><td style="background:#f8f9fa;padding:20px 40px;text-align:center;font-size:12px;color:#999;">
            This inquiry was submitted via the website contact form.<br>
            &copy; ' . date('Y') . ' Maxi Wheelchair Cabs Perth
        </td></tr>
    </table>
    </body></html>';
}

function getCustomerEmailTemplate($name, $service, $company, $phone) {
    return '<!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <tr><td style="background:#1a1a2e;padding:30px 40px;text-align:center;">
            <h1 style="color:#FFC107;margin:0;font-size:22px;">' . htmlspecialchars($company) . '</h1>
            <p style="color:rgba(255,255,255,0.7);margin:5px 0 0;font-size:13px;">Booking Confirmation</p>
        </td></tr>
        <tr><td style="padding:35px 40px;">
            <h2 style="color:#1a1a2e;margin:0 0 15px;font-size:20px;">Thank You, ' . htmlspecialchars($name) . '!</h2>
            <p style="color:#555;line-height:1.8;font-size:15px;">We have received your booking inquiry' . ($service ? ' for <strong>' . htmlspecialchars($service) . '</strong>' : '') . ' and our team is reviewing it now.</p>
            <p style="color:#555;line-height:1.8;font-size:15px;">One of our friendly staff will be in touch with you shortly to confirm your ride details.</p>
            <div style="margin:25px 0;padding:20px;background:#FFC107;border-radius:10px;text-align:center;">
                <p style="margin:0;color:#1a1a2e;font-size:14px;font-weight:bold;">Need it sooner? Call us directly:</p>
                <p style="margin:8px 0 0;font-size:24px;font-weight:bold;color:#1a1a2e;">' . htmlspecialchars($phone) . '</p>
            </div>
            <p style="color:#999;font-size:13px;line-height:1.6;">We are available 24 hours a day, 7 days a week. Do not hesitate to call if you have any questions.</p>
        </td></tr>
        <tr><td style="background:#f8f9fa;padding:20px 40px;text-align:center;font-size:12px;color:#999;">
            &copy; ' . date('Y') . ' ' . htmlspecialchars($company) . ' | Perth, Western Australia
        </td></tr>
    </table>
    </body></html>';
}

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

            <!-- Contact Form -->
            <div class="contact-form" data-aos="fade-left">
                <h3>Book Your Ride</h3>
                <p>Fill in the details below and we will get back to you as soon as possible.</p>

                <?php if ($form_success): ?>
                <div class="form-alert success"><i class="fas fa-check-circle"></i> <?php echo e($form_success); ?></div>
                <?php endif; ?>
                <?php if ($form_error): ?>
                <div class="form-alert error"><i class="fas fa-exclamation-circle"></i> <?php echo e($form_error); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo e($name ?? ''); ?>" required placeholder="Your full name">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo e($phone ?? ''); ?>" required placeholder="Your phone number">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo e($email ?? ''); ?>" placeholder="Your email">
                        </div>
                        <div class="form-group">
                            <label for="service">Service Required</label>
                            <select id="service" name="service">
                                <option value="">Select a service</option>
                                <?php foreach ($services as $svc): ?>
                                <option value="<?php echo e($svc['title']); ?>" <?php echo (isset($service) && $service === $svc['title']) ? 'selected' : ''; ?>><?php echo e($svc['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pickup_location">Pickup Location</label>
                            <input type="text" id="pickup_location" name="pickup_location" value="<?php echo e($pickup ?? ''); ?>" placeholder="Where do we pick you up?">
                        </div>
                        <div class="form-group">
                            <label for="dropoff_location">Drop-off Location</label>
                            <input type="text" id="dropoff_location" name="dropoff_location" value="<?php echo e($dropoff ?? ''); ?>" placeholder="Where are you going?">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="travel_date">Travel Date & Time</label>
                            <input type="datetime-local" id="travel_date" name="travel_date" value="<?php echo e($travel_date ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="passengers">Number of Passengers</label>
                            <select id="passengers" name="passengers">
                                <option value="">Select</option>
                                <?php for ($p = 1; $p <= 13; $p++): ?>
                                <option value="<?php echo $p; ?>" <?php echo (isset($passengers) && $passengers == $p) ? 'selected' : ''; ?>><?php echo $p; ?> <?php echo $p === 1 ? 'Passenger' : 'Passengers'; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Additional Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Any special requirements or notes..."><?php echo e($message ?? ''); ?></textarea>
                    </div>
                    <div class="form-submit">
                        <button type="submit" name="submit_inquiry" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Booking Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
