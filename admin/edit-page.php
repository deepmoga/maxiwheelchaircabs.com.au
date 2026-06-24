<?php
require_once 'includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
$page = null;
$error = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->execute([$id]);
    $page = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($title)) {
        $error = 'Title is required.';
    } else {
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
            $slug = trim($slug, '-');
        }

        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE pages SET title=?, slug=?, content=?, meta_title=?, meta_description=?, meta_keywords=?, status=? WHERE id=?");
                $stmt->execute([$title, $slug, $content, $meta_title, $meta_description, $meta_keywords, $status, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO pages (title, slug, content, meta_title, meta_description, meta_keywords, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $content, $meta_title, $meta_description, $meta_keywords, $status]);
            }
            header('Location: pages.php?msg=saved');
            exit;
        } catch (PDOException $e) {
            $error = 'Error saving. Slug might already exist.';
        }
    }
}

include 'includes/header.php';
?>

<div class="page-title">
    <h2><?php echo $id ? 'Edit Page' : 'Add New Page'; ?></h2>
    <a href="pages.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo e($error); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card">
                <h3>Page Content</h3>
                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo e($page['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo e($page['slug'] ?? ''); ?>" placeholder="auto-generated">
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="tinymce-editor"><?php echo $page['content'] ?? ''; ?></textarea>
                </div>
            </div>

            <div class="admin-card">
                <h3><i class="fas fa-search" style="color:var(--admin-primary);margin-right:8px;"></i> SEO Settings</h3>
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?php echo e($page['meta_title'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?php echo e($page['meta_description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="<?php echo e($page['meta_keywords'] ?? ''); ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card">
                <h3>Publish</h3>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="status" class="form-check-input" id="status" <?php echo ($page['status'] ?? 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Active / Published</label>
                </div>
                <button type="submit" class="btn btn-admin w-100"><i class="fas fa-save"></i> Save Page</button>
            </div>
        </div>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
