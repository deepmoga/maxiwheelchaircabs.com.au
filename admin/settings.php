<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['site_name', 'phone_1', 'phone_2', 'email', 'address', 'map_embed', 'facebook_url', 'instagram_url', 'whatsapp_number', 'twitter_url', 'linkedin_url', 'youtube_url'];

    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $stmt->execute([$field, $_POST[$field]]);
        }
    }

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../images/';
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . 'logo.' . $ext);
        }
    }

    $success = 'Settings updated successfully!';
}

$s = getAllSettings();
include 'includes/header.php';
?>

<div class="page-title"><h2>Site Settings</h2></div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <!-- General Settings -->
    <div class="admin-card">
        <h3><i class="fas fa-cog" style="color:var(--admin-primary);margin-right:8px;"></i> General Settings</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control" value="<?php echo e($s['site_name'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Logo Upload</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="form-text text-muted">Current logo is in images/logo.png</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number 1</label>
                <input type="text" name="phone_1" class="form-control" value="<?php echo e($s['phone_1'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number 2</label>
                <input type="text" name="phone_2" class="form-control" value="<?php echo e($s['phone_2'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?php echo e($s['email'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo e($s['address'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Google Map Embed Code (iframe)</label>
                <textarea name="map_embed" class="form-control" rows="3"><?php echo e($s['map_embed'] ?? ''); ?></textarea>
                <small class="form-text text-muted">Paste the full iframe code from Google Maps</small>
            </div>
        </div>
    </div>

    <!-- Social Media -->
    <div class="admin-card">
        <h3><i class="fas fa-share-alt" style="color:var(--admin-primary);margin-right:8px;"></i> Social Media Links</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-facebook" style="color:#1877f2;"></i> Facebook URL</label>
                <input type="url" name="facebook_url" class="form-control" value="<?php echo e($s['facebook_url'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-instagram" style="color:#e4405f;"></i> Instagram URL</label>
                <input type="url" name="instagram_url" class="form-control" value="<?php echo e($s['instagram_url'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-whatsapp" style="color:#25d366;"></i> WhatsApp Number</label>
                <input type="text" name="whatsapp_number" class="form-control" value="<?php echo e($s['whatsapp_number'] ?? ''); ?>" placeholder="e.g. 61412345678">
                <small class="form-text text-muted">International format without + sign</small>
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter/X URL</label>
                <input type="url" name="twitter_url" class="form-control" value="<?php echo e($s['twitter_url'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-linkedin" style="color:#0a66c2;"></i> LinkedIn URL</label>
                <input type="url" name="linkedin_url" class="form-control" value="<?php echo e($s['linkedin_url'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube URL</label>
                <input type="url" name="youtube_url" class="form-control" value="<?php echo e($s['youtube_url'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-admin btn-lg"><i class="fas fa-save"></i> Save Settings</button>
</form>

<?php include 'includes/footer.php'; ?>
