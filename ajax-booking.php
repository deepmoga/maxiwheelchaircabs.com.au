<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$pickup = trim($_POST['pickup_location'] ?? '');
$dropoff = trim($_POST['dropoff_location'] ?? '');
$travel_date = trim($_POST['travel_date'] ?? '');
$travel_time = trim($_POST['travel_time'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($phone) || empty($pickup) || empty($dropoff)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$full_date = $travel_date . ($travel_time ? ' ' . $travel_time : '');

try {
    $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, service, pickup_location, dropoff_location, travel_date, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, 'Hero Booking Form', $pickup, $dropoff, $full_date, $message]);

    // Send emails
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
        $site_phone = getSetting('phone_1', '');

        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int)$smtp_port;
        $mail->setFrom($from_email, $from_name);

        // --- Admin email ---
        $mail->addAddress($admin_email);
        if ($email) $mail->addReplyTo($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'New Ride Request from ' . $name;
        $mail->Body = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>
        <body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
            <tr><td style="background:#1a1a2e;padding:25px 35px;text-align:center;">
                <h1 style="color:#FFC107;margin:0;font-size:20px;">New Ride Request</h1>
                <p style="color:rgba(255,255,255,0.6);margin:5px 0 0;font-size:13px;">via Website Booking Form</p>
            </td></tr>
            <tr><td style="padding:30px 35px;">
                <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;color:#333;">
                    <tr style="background:#f8f9fa;"><td style="font-weight:bold;width:120px;padding:10px;border-bottom:1px solid #eee;">Name</td><td style="padding:10px;border-bottom:1px solid #eee;">' . htmlspecialchars($name) . '</td></tr>
                    <tr><td style="font-weight:bold;padding:10px;border-bottom:1px solid #eee;">Phone</td><td style="padding:10px;border-bottom:1px solid #eee;"><a href="tel:' . htmlspecialchars($phone) . '" style="color:#1a1a2e;font-weight:bold;">' . htmlspecialchars($phone) . '</a></td></tr>
                    <tr style="background:#f8f9fa;"><td style="font-weight:bold;padding:10px;border-bottom:1px solid #eee;">Email</td><td style="padding:10px;border-bottom:1px solid #eee;">' . htmlspecialchars($email ?: 'Not provided') . '</td></tr>
                    <tr><td style="font-weight:bold;padding:10px;border-bottom:1px solid #eee;">From</td><td style="padding:10px;border-bottom:1px solid #eee;">' . htmlspecialchars($pickup) . '</td></tr>
                    <tr style="background:#f8f9fa;"><td style="font-weight:bold;padding:10px;border-bottom:1px solid #eee;">To</td><td style="padding:10px;border-bottom:1px solid #eee;">' . htmlspecialchars($dropoff) . '</td></tr>
                    <tr><td style="font-weight:bold;padding:10px;border-bottom:1px solid #eee;">Date & Time</td><td style="padding:10px;border-bottom:1px solid #eee;"><strong>' . htmlspecialchars($full_date) . '</strong></td></tr>
                </table>
                ' . ($message ? '<div style="margin-top:18px;padding:14px;background:#f8f9fa;border-radius:8px;border-left:4px solid #FFC107;"><strong style="color:#1a1a2e;">Message:</strong><p style="margin:6px 0 0;color:#555;line-height:1.6;">' . nl2br(htmlspecialchars($message)) . '</p></div>' : '') . '
            </td></tr>
            <tr><td style="background:#f8f9fa;padding:15px 35px;text-align:center;font-size:11px;color:#999;">&copy; ' . date('Y') . ' Maxi Wheelchair Cabs Perth</td></tr>
        </table></body></html>';

        $mail->send();

        // --- Customer confirmation email ---
        if ($email) {
            $mail->clearAddresses();
            $mail->clearReplyTos();
            $mail->addAddress($email, $name);
            $mail->Subject = 'Ride Request Received - ' . $from_name;
            $mail->Body = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>
            <body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <tr><td style="background:#1a1a2e;padding:25px 35px;text-align:center;">
                    <h1 style="color:#FFC107;margin:0;font-size:20px;">' . htmlspecialchars($from_name) . '</h1>
                    <p style="color:rgba(255,255,255,0.6);margin:5px 0 0;font-size:13px;">Ride Request Received</p>
                </td></tr>
                <tr><td style="padding:30px 35px;">
                    <h2 style="color:#1a1a2e;margin:0 0 12px;font-size:19px;">Thank You, ' . htmlspecialchars($name) . '!</h2>
                    <p style="color:#555;line-height:1.8;font-size:15px;">We have received your ride request and our team is reviewing it now.</p>
                    <div style="margin:20px 0;padding:18px;background:#fffbe6;border-radius:10px;border:1px solid #FFC107;">
                        <table width="100%" cellpadding="5" cellspacing="0" style="font-size:14px;color:#333;">
                            <tr><td style="font-weight:bold;width:80px;">From:</td><td>' . htmlspecialchars($pickup) . '</td></tr>
                            <tr><td style="font-weight:bold;">To:</td><td>' . htmlspecialchars($dropoff) . '</td></tr>
                            <tr><td style="font-weight:bold;">When:</td><td>' . htmlspecialchars($full_date) . '</td></tr>
                        </table>
                    </div>
                    <p style="color:#555;line-height:1.8;font-size:15px;"><strong style="color:#e74c3c;">Please wait for confirmation.</strong> One of our friendly team members will call or email you shortly to confirm your ride details and fare.</p>
                    <div style="margin:22px 0;padding:18px;background:#FFC107;border-radius:10px;text-align:center;">
                        <p style="margin:0;color:#1a1a2e;font-size:13px;font-weight:bold;">Need it urgently? Call us directly:</p>
                        <p style="margin:6px 0 0;font-size:22px;font-weight:bold;color:#1a1a2e;">' . htmlspecialchars($site_phone) . '</p>
                    </div>
                    <p style="color:#999;font-size:12px;">We are available 24/7. Do not hesitate to reach out if you have questions.</p>
                </td></tr>
                <tr><td style="background:#f8f9fa;padding:15px 35px;text-align:center;font-size:11px;color:#999;">&copy; ' . date('Y') . ' ' . htmlspecialchars($from_name) . ' | Perth, WA</td></tr>
            </table></body></html>';

            $mail->send();
        }
    } catch (Exception $e) {
        // Email failed but inquiry saved
    }

    echo json_encode(['success' => true, 'message' => 'Thank you, ' . $name . '! Your ride request has been submitted. Please wait for confirmation — we will contact you shortly.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again or call us directly.']);
}
