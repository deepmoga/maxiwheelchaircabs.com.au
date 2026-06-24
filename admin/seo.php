<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['home_meta_title', 'home_meta_description', 'home_meta_keywords'];
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $stmt->execute([$field, $_POST[$field]]);
        }
    }
    $success = 'SEO settings updated!';
}

$s = getAllSettings();
include 'includes/header.php';
?>

<div class="page-title"><h2>SEO Settings</h2></div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="admin-card">
        <h3><i class="fas fa-search" style="color:var(--admin-primary);margin-right:8px;"></i> Homepage SEO</h3>
        <div class="mb-3">
            <label class="form-label">Meta Title</label>
            <input type="text" name="home_meta_title" class="form-control" value="<?php echo e($s['home_meta_title'] ?? ''); ?>">
            <small class="form-text text-muted">This appears in browser tabs and search results. Recommended: 50-60 characters.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Meta Description</label>
            <textarea name="home_meta_description" class="form-control" rows="3"><?php echo e($s['home_meta_description'] ?? ''); ?></textarea>
            <small class="form-text text-muted">Shown in Google search results below the title. Recommended: 150-160 characters.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Meta Keywords</label>
            <textarea name="home_meta_keywords" class="form-control" rows="2"><?php echo e($s['home_meta_keywords'] ?? ''); ?></textarea>
            <small class="form-text text-muted">Comma-separated keywords. E.g.: wheelchair taxi Perth, maxi cab Perth, airport transfer</small>
        </div>
    </div>

    <div class="admin-card">
        <h3><i class="fas fa-info-circle" style="color:var(--admin-primary);margin-right:8px;"></i> Service & Page SEO</h3>
        <p style="color:var(--admin-text-light);font-size:14px;">
            Each service and page has its own SEO fields (Meta Title, Meta Description, Meta Keywords).
            You can edit them individually from the <a href="services.php">Services</a> and <a href="pages.php">Pages</a> sections.
        </p>
    </div>

    <button type="submit" class="btn btn-admin btn-lg"><i class="fas fa-save"></i> Save SEO Settings</button>
</form>

<?php include 'includes/footer.php'; ?>
