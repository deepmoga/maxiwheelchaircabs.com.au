<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';

// Delete service
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: services.php?msg=deleted');
    exit;
}

// Toggle status
if (isset($_GET['toggle'])) {
    $pdo->prepare("UPDATE services SET status = IF(status=1,0,1) WHERE id = ?")->execute([$_GET['toggle']]);
    header('Location: services.php?msg=updated');
    exit;
}

if (isset($_GET['msg'])) $success = 'Service ' . $_GET['msg'] . ' successfully!';

$services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();
include 'includes/header.php';
?>

<div class="page-title">
    <h2>Services</h2>
    <a href="edit-service.php" class="btn btn-admin"><i class="fas fa-plus"></i> Add New Service</a>
</div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e($success); ?></div>
<?php endif; ?>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr><th>Order</th><th>Image</th><th>Title</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($services as $svc): ?>
            <tr>
                <td><?php echo $svc['sort_order']; ?></td>
                <td>
                    <?php if ($svc['image']): ?>
                    <img src="../<?php echo e($svc['image']); ?>" class="img-preview" style="width:80px;height:50px;">
                    <?php else: ?>
                    <i class="<?php echo e($svc['icon']); ?>" style="font-size:24px;color:var(--admin-primary);"></i>
                    <?php endif; ?>
                </td>
                <td><strong><?php echo e($svc['title']); ?></strong><br><small style="color:var(--admin-text-light);">/service/<?php echo e($svc['slug']); ?></small></td>
                <td>
                    <a href="?toggle=<?php echo $svc['id']; ?>">
                        <span class="badge-status <?php echo $svc['status'] ? 'badge-active' : 'badge-inactive'; ?>">
                            <?php echo $svc['status'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </a>
                </td>
                <td>
                    <div class="action-btns">
                        <a href="edit-service.php?id=<?php echo $svc['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?php echo $svc['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
