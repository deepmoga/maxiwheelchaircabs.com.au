<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';
$testResult = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        $fields = ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_from_email', 'smtp_from_name', 'admin_email'];
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $stmt->execute([$field, $_POST[$field]]);
            }
        }
        $success = 'Mail settings updated!';
    }

    // Test email
    if (isset($_POST['send_test'])) {
        try {
            require_once __DIR__ . '/../phpmailer/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = getSetting('smtp_host');
            $mail->SMTPAuth = true;
            $mail->Username = getSetting('smtp_username');
            $mail->Password = getSetting('smtp_password');
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int)getSetting('smtp_port');

            $mail->setFrom(getSetting('smtp_from_email'), getSetting('smtp_from_name'));
            $mail->addAddress(getSetting('admin_email'));
            $mail->isHTML(true);
            $mail->Subject = 'Test Email - Maxi Wheelchair Cabs';
            $mail->Body = '<div style="font-family:Arial;padding:30px;text-align:center;"><h2 style="color:#1a1a2e;">Mail Test Successful!</h2><p style="color:#666;">Your email settings are working correctly.</p><p style="color:#FFC107;font-weight:bold;">Maxi Wheelchair Cabs</p></div>';

            $mail->send();
            $testResult = 'success';
        } catch (Exception $e) {
            $testResult = 'Error: ' . $e->getMessage();
        }
    }
}

$s = getAllSettings();
include 'includes/header.php';
?>

<div class="page-title"><h2>Mail Settings</h2></div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($testResult === 'success'): ?>
<div class="alert alert-success">Test email sent successfully to <?php echo e(getSetting('admin_email')); ?>!</div>
<?php elseif ($testResult): ?>
<div class="alert alert-danger"><?php echo e($testResult); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="admin-card">
        <h3><i class="fas fa-server" style="color:var(--admin-primary);margin-right:8px;"></i> SMTP Configuration</h3>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" value="<?php echo e($s['smtp_host'] ?? 'smtp.gmail.com'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">SMTP Port</label>
                <input type="text" name="smtp_port" class="form-control" value="<?php echo e($s['smtp_port'] ?? '587'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">SMTP Username</label>
                <input type="text" name="smtp_username" class="form-control" value="<?php echo e($s['smtp_username'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">SMTP Password</label>
                <input type="password" name="smtp_password" class="form-control" value="<?php echo e($s['smtp_password'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3><i class="fas fa-envelope" style="color:var(--admin-primary);margin-right:8px;"></i> Email Settings</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">From Email</label>
                <input type="email" name="smtp_from_email" class="form-control" value="<?php echo e($s['smtp_from_email'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">From Name</label>
                <input type="text" name="smtp_from_name" class="form-control" value="<?php echo e($s['smtp_from_name'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Admin Notification Email</label>
                <input type="email" name="admin_email" class="form-control" value="<?php echo e($s['admin_email'] ?? ''); ?>">
                <small class="form-text text-muted">Inquiry notifications will be sent to this email</small>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" name="save_settings" class="btn btn-admin btn-lg"><i class="fas fa-save"></i> Save Settings</button>
        <button type="submit" name="send_test" class="btn btn-outline-primary btn-lg"><i class="fas fa-paper-plane"></i> Send Test Email</button>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
