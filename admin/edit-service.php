<?php
require_once 'includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
$service = null;
$success = '';
$error = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $description = $_POST['description'] ?? '';
    $icon = trim($_POST['icon'] ?? 'fas fa-taxi');
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($title)) {
        $error = 'Title is required.';
    } else {
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
            $slug = trim($slug, '-');
        }

        $image = $service['image'] ?? '';
        $banner_image = $service['banner_image'] ?? '';
        $uploadDir = __DIR__ . '/../uploads/';

        // Handle thumbnail image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'service-' . $slug . '-' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
                $image = 'uploads/' . $filename;
            }
        }

        // Handle banner image upload
        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'service-banner-' . $slug . '-' . time() . '.' . $ext;
                move_uploaded_file($_FILES['banner_image']['tmp_name'], $uploadDir . $filename);
                $banner_image = 'uploads/' . $filename;
            }
        }

        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE services SET title=?, slug=?, short_description=?, description=?, icon=?, image=?, banner_image=?, meta_title=?, meta_description=?, meta_keywords=?, sort_order=?, status=? WHERE id=?");
                $stmt->execute([$title, $slug, $short_description, $description, $icon, $image, $banner_image, $meta_title, $meta_description, $meta_keywords, $sort_order, $status, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO services (title, slug, short_description, description, icon, image, banner_image, meta_title, meta_description, meta_keywords, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $short_description, $description, $icon, $image, $banner_image, $meta_title, $meta_description, $meta_keywords, $sort_order, $status]);
                $id = $pdo->lastInsertId();
            }
            header('Location: services.php?msg=saved');
            exit;
        } catch (PDOException $e) {
            $error = 'Error saving service. Slug might already exist.';
        }
    }
}

include 'includes/header.php';
?>

<div class="page-title">
    <h2><?php echo $id ? 'Edit Service' : 'Add New Service'; ?></h2>
    <a href="services.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo e($error); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card">
                <h3>Service Details</h3>
                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo e($service['title'] ?? $title ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo e($service['slug'] ?? $slug ?? ''); ?>" placeholder="auto-generated from title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Short Description (for cards)</label>
                    <textarea name="short_description" class="form-control" rows="3"><?php echo e($service['short_description'] ?? $short_description ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Description (Service Page Content)</label>
                    <textarea name="description" class="tinymce-editor"><?php echo $service['description'] ?? $description ?? ''; ?></textarea>
                </div>
            </div>

            <!-- SEO -->
            <div class="admin-card">
                <h3><i class="fas fa-search" style="color:var(--admin-primary);margin-right:8px;"></i> SEO Settings</h3>
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?php echo e($service['meta_title'] ?? $meta_title ?? ''); ?>">
                    <small class="form-text text-muted">Recommended: 50-60 characters</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?php echo e($service['meta_description'] ?? $meta_description ?? ''); ?></textarea>
                    <small class="form-text text-muted">Recommended: 150-160 characters</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="<?php echo e($service['meta_keywords'] ?? $meta_keywords ?? ''); ?>">
                    <small class="form-text text-muted">Comma-separated keywords</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <h3>Settings</h3>
                <div class="mb-3">
                    <label class="form-label">Icon Class (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control" value="<?php echo e($service['icon'] ?? 'fas fa-taxi'); ?>">
                    <small class="form-text text-muted">e.g., fas fa-wheelchair</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?php echo $service['sort_order'] ?? 0; ?>">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="status" class="form-check-input" id="status" <?php echo ($service['status'] ?? 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Active</label>
                </div>
            </div>

            <div class="admin-card">
                <h3>Thumbnail Image</h3>
                <p style="font-size:12px;color:var(--admin-text-light);margin-top:-15px;margin-bottom:12px;">Shown on homepage & services listing cards</p>
                <div class="mb-3">
                    <?php if (!empty($service['image'])): ?>
                    <div style="margin-bottom:12px;">
                        <img src="<?php echo SITE_URL . '/' . e($service['image']); ?>" class="img-preview" style="width:100%;height:150px;object-fit:cover;display:block;">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Recommended: 400x300px</small>
                </div>
            </div>

            <div class="admin-card">
                <h3>Banner Image</h3>
                <p style="font-size:12px;color:var(--admin-text-light);margin-top:-15px;margin-bottom:12px;">Large image shown at top of the single service page</p>
                <div class="mb-3">
                    <?php if (!empty($service['banner_image'])): ?>
                    <div style="margin-bottom:12px;">
                        <img src="<?php echo SITE_URL . '/' . e($service['banner_image']); ?>" class="img-preview" style="width:100%;height:150px;object-fit:cover;display:block;">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="banner_image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Recommended: 1200x400px. Displayed at top of service detail page.</small>
                </div>
            </div>

            <button type="submit" class="btn btn-admin btn-lg w-100"><i class="fas fa-save"></i> Save Service</button>
        </div>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
