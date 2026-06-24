<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['hero_title', 'hero_badge', 'hero_description', 'about_title', 'about_subtitle', 'about_description', 'cta_title', 'cta_description', 'cta2_title', 'cta2_description', 'whychoose_title', 'whychoose_subtitle', 'whychoose_description', 'feature_1_title', 'feature_1_desc', 'feature_2_title', 'feature_2_desc', 'feature_3_title', 'feature_3_desc', 'feature_4_title', 'feature_4_desc', 'feature_5_title', 'feature_5_desc', 'feature_6_title', 'feature_6_desc'];

    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $stmt->execute([$field, $_POST[$field]]);
        }
    }

    // Handle image uploads
    $imageFields = [
        'hero_image_file' => ['hero_image', 'hero-bg'],
        'hero_car_file' => ['hero_car_image', 'hero-car'],
        'about_image_file' => ['about_image', 'about-main'],
        'about_image_small_file' => ['about_image_small', 'about-small'],
        'whychoose_image_file' => ['whychoose_image', 'why-choose'],
    ];

    $uploadDir = __DIR__ . '/../images/';
    foreach ($imageFields as $fileKey => $config) {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = $config[1] . '.' . $ext;
                move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadDir . $filename);
                $stmt->execute([$config[0], 'images/' . $filename]);
            }
        }
    }

    $success = 'Homepage content updated successfully!';
}

$s = getAllSettings();
include 'includes/header.php';
?>

<div class="page-title"><h2>Homepage Content</h2></div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <!-- Hero Section -->
    <div class="admin-card">
        <h3><i class="fas fa-image" style="color:var(--admin-primary);margin-right:8px;"></i> Hero Section</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Hero Badge Text</label>
                <input type="text" name="hero_badge" class="form-control" value="<?php echo e($s['hero_badge'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Hero Title (use &lt;span&gt; for yellow text)</label>
                <input type="text" name="hero_title" class="form-control" value="<?php echo e($s['hero_title'] ?? ''); ?>">
                <small class="form-text text-muted">Example: Professional &lt;span&gt;Wheelchair Taxi&lt;/span&gt; & Maxi Cab Service</small>
            </div>
            <div class="col-12">
                <label class="form-label">Hero Description</label>
                <textarea name="hero_description" class="form-control" rows="3"><?php echo e($s['hero_description'] ?? ''); ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Hero Background Image</label>
                <input type="file" name="hero_image_file" class="form-control" accept="image/*">
                <?php if (!empty($s['hero_image'])): ?>
                <img src="../<?php echo e($s['hero_image']); ?>" class="img-preview" style="margin-top:8px;">
                <?php endif; ?>
                <small class="form-text text-muted">Dark background image behind the hero text (1920x1080 recommended)</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Hero Car Image (right side)</label>
                <input type="file" name="hero_car_file" class="form-control" accept="image/*">
                <?php if (!empty($s['hero_car_image'])): ?>
                <img src="../<?php echo e($s['hero_car_image']); ?>" class="img-preview" style="margin-top:8px;">
                <?php endif; ?>
                <small class="form-text text-muted">Car/taxi image shown on the right side of hero (600x400, transparent PNG recommended)</small>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="admin-card">
        <h3><i class="fas fa-info-circle" style="color:var(--admin-primary);margin-right:8px;"></i> About Section</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">About Subtitle</label>
                <input type="text" name="about_subtitle" class="form-control" value="<?php echo e($s['about_subtitle'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">About Title</label>
                <input type="text" name="about_title" class="form-control" value="<?php echo e($s['about_title'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">About Main Image</label>
                <input type="file" name="about_image_file" class="form-control" accept="image/*">
                <?php if (!empty($s['about_image'])): ?>
                <img src="../<?php echo e($s['about_image']); ?>" class="img-preview" style="margin-top:8px;">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">About Small Image (overlay)</label>
                <input type="file" name="about_image_small_file" class="form-control" accept="image/*">
                <?php if (!empty($s['about_image_small'])): ?>
                <img src="../<?php echo e($s['about_image_small']); ?>" class="img-preview" style="margin-top:8px;">
                <?php endif; ?>
            </div>
            <div class="col-12">
                <label class="form-label">About Description</label>
                <textarea name="about_description" class="tinymce-editor"><?php echo $s['about_description'] ?? ''; ?></textarea>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="admin-card">
        <h3><i class="fas fa-trophy" style="color:var(--admin-primary);margin-right:8px;"></i> Why Choose Us Section</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Section Title</label>
                <input type="text" name="whychoose_title" class="form-control" value="<?php echo e($s['whychoose_title'] ?? 'Honest Pricing, Experienced Drivers & Reliable Service Every Time'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Section Image</label>
                <input type="file" name="whychoose_image_file" class="form-control" accept="image/*">
                <?php if (!empty($s['whychoose_image'])): ?>
                <img src="../<?php echo e($s['whychoose_image']); ?>" class="img-preview" style="margin-top:8px;">
                <?php endif; ?>
            </div>
            <div class="col-12">
                <label class="form-label">Section Description</label>
                <textarea name="whychoose_description" class="form-control" rows="2"><?php echo e($s['whychoose_description'] ?? 'We are not just another taxi company. We are Perth locals who understand your travel needs and deliver a level of care that bigger operators simply cannot match.'); ?></textarea>
            </div>
        </div>

        <h5 class="mt-4 mb-3" style="font-weight:600;">Feature Items (6 features)</h5>

        <?php
        $defaultFeatures = [
            ['No Hidden Charges', 'What we quote is what you pay. Transparent metered or fixed fares, always.'],
            ['Professional Drivers', 'Trained, licensed and background-checked for your complete safety.'],
            ['24/7 Availability', 'Early morning flight or late night ride, we are always just a call away.'],
            ['Driver SMS Alerts', 'Get a text when your driver is 10 minutes away. No guessing, no waiting.'],
            ['Clean Modern Fleet', 'Well-maintained vehicles from sedans to 13-seater maxi cabs ready to go.'],
            ['All Payment Methods', 'Cash, card, EFTPOS — pay however works best for you.'],
        ];
        ?>

        <div class="row g-3">
            <?php for ($i = 1; $i <= 6; $i++): ?>
            <div class="col-md-6">
                <div class="settings-group">
                    <h4><i class="fas fa-star"></i> Feature <?php echo $i; ?></h4>
                    <div class="mb-2">
                        <label class="form-label">Title</label>
                        <input type="text" name="feature_<?php echo $i; ?>_title" class="form-control" value="<?php echo e($s['feature_' . $i . '_title'] ?? $defaultFeatures[$i-1][0]); ?>">
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <input type="text" name="feature_<?php echo $i; ?>_desc" class="form-control" value="<?php echo e($s['feature_' . $i . '_desc'] ?? $defaultFeatures[$i-1][1]); ?>">
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- CTA Sections -->
    <div class="admin-card">
        <h3><i class="fas fa-bullhorn" style="color:var(--admin-primary);margin-right:8px;"></i> Call-to-Action Sections</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">CTA 1 Title</label>
                <input type="text" name="cta_title" class="form-control" value="<?php echo e($s['cta_title'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA 1 Description</label>
                <input type="text" name="cta_description" class="form-control" value="<?php echo e($s['cta_description'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA 2 Title</label>
                <input type="text" name="cta2_title" class="form-control" value="<?php echo e($s['cta2_title'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA 2 Description</label>
                <input type="text" name="cta2_description" class="form-control" value="<?php echo e($s['cta2_description'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-admin btn-lg"><i class="fas fa-save"></i> Save Homepage Content</button>
</form>

<?php include 'includes/footer.php'; ?>
