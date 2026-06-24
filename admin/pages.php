<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM pages WHERE id = ?")->execute([$_GET['delete']]);
    header('Location: pages.php?msg=deleted');
    exit;
}

if (isset($_GET['msg'])) $success = 'Page ' . $_GET['msg'] . ' successfully!';

$pages = $pdo->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
include 'includes/header.php';
?>

<div class="page-title">
    <h2>Pages</h2>
    <a href="edit-page.php" class="btn btn-admin"><i class="fas fa-plus"></i> Add New Page</a>
</div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e($success); ?></div>
<?php endif; ?>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($pages as $p): ?>
            <tr>
                <td><strong><?php echo e($p['title']); ?></strong></td>
                <td>/<?php echo e($p['slug']); ?></td>
                <td><span class="badge-status <?php echo $p['status'] ? 'badge-active' : 'badge-inactive'; ?>"><?php echo $p['status'] ? 'Active' : 'Draft'; ?></span></td>
                <td>
                    <div class="action-btns">
                        <a href="edit-page.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
