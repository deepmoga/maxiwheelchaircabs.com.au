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

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = 'service-' . $slug . '-' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
                $image = 'uploads/' . $filename;
            }
        }

        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE services SET title=?, slug=?, short_description=?, description=?, icon=?, image=?, meta_title=?, meta_description=?, meta_keywords=?, sort_order=?, status=? WHERE id=?");
                $stmt->execute([$title, $slug, $short_description, $description, $icon, $image, $meta_title, $meta_description, $meta_keywords, $sort_order, $status, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO services (title, slug, short_description, description, icon, image, meta_title, meta_description, meta_keywords, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $short_description, $description, $icon, $image, $meta_title, $meta_description, $meta_keywords, $sort_order, $status]);
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
                <h3>Service Image</h3>
                <div class="mb-3">
                    <?php if (!empty($service['image'])): ?>
                    <div style="margin-bottom:12px;">
                        <label class="form-label" style="color:var(--admin-text-light);font-size:12px;">Current Image:</label>
                        <img src="<?php echo SITE_URL . '/' . e($service['image']); ?>" class="img-preview" style="width:100%;height:180px;object-fit:cover;margin-top:4px;display:block;">
                        <small class="form-text text-muted"><?php echo e($service['image']); ?></small>
                    </div>
                    <?php endif; ?>
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Recommended: 400x300px. Leave empty to keep current image.</small>
                </div>
            </div>

            <button type="submit" class="btn btn-admin btn-lg w-100"><i class="fas fa-save"></i> Save Service</button>
        </div>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
