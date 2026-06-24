<?php
require_once 'includes/auth.php';
requireLogin();

// Delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM inquiries WHERE id = ?")->execute([$_GET['delete']]);
    header('Location: inquiries.php');
    exit;
}

// Mark as read
if (isset($_GET['mark'])) {
    $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?")->execute([$_GET['mark'], $_GET['id']]);
    header('Location: inquiries.php?view=' . $_GET['id']);
    exit;
}

// View single
$viewItem = null;
if (isset($_GET['view'])) {
    $stmt = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
    $stmt->execute([$_GET['view']]);
    $viewItem = $stmt->fetch();
    if ($viewItem && $viewItem['status'] === 'new') {
        $pdo->prepare("UPDATE inquiries SET status = 'read' WHERE id = ?")->execute([$viewItem['id']]);
        $viewItem['status'] = 'read';
    }
}

$inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
include 'includes/header.php';
?>

<div class="page-title"><h2>Inquiries</h2></div>

<?php if ($viewItem): ?>
<div class="admin-card">
    <h3>Inquiry from <?php echo e($viewItem['name']); ?></h3>
    <a href="inquiries.php" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to list</a>

    <div class="row g-3">
        <div class="col-md-6"><strong>Name:</strong> <?php echo e($viewItem['name']); ?></div>
        <div class="col-md-6"><strong>Phone:</strong> <a href="tel:<?php echo e($viewItem['phone']); ?>"><?php echo e($viewItem['phone']); ?></a></div>
        <div class="col-md-6"><strong>Email:</strong> <?php echo e($viewItem['email'] ?: 'Not provided'); ?></div>
        <div class="col-md-6"><strong>Service:</strong> <?php echo e($viewItem['service'] ?: 'General'); ?></div>
        <div class="col-md-6"><strong>Pickup:</strong> <?php echo e($viewItem['pickup_location'] ?: 'Not specified'); ?></div>
        <div class="col-md-6"><strong>Drop-off:</strong> <?php echo e($viewItem['dropoff_location'] ?: 'Not specified'); ?></div>
        <div class="col-md-6"><strong>Travel Date:</strong> <?php echo e($viewItem['travel_date'] ?: 'Not specified'); ?></div>
        <div class="col-md-6"><strong>Passengers:</strong> <?php echo e($viewItem['passengers'] ?: 'Not specified'); ?></div>
        <div class="col-12"><strong>Message:</strong><p style="background:#f8f9fa;padding:15px;border-radius:8px;margin-top:5px;"><?php echo nl2br(e($viewItem['message'] ?: 'No message')); ?></p></div>
        <div class="col-12">
            <strong>Status:</strong> <span class="badge-status badge-<?php echo $viewItem['status']; ?>"><?php echo ucfirst($viewItem['status']); ?></span>
            <strong style="margin-left:20px;">Received:</strong> <?php echo date('d M Y, H:i', strtotime($viewItem['created_at'])); ?>
        </div>
        <div class="col-12 mt-3">
            <a href="?mark=replied&id=<?php echo $viewItem['id']; ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Mark Replied</a>
            <a href="?delete=<?php echo $viewItem['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this inquiry?')"><i class="fas fa-trash"></i> Delete</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="admin-card">
    <h3>All Inquiries (<?php echo count($inquiries); ?>)</h3>
    <div class="table-responsive">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Phone</th><th>Service</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($inquiries as $inq): ?>
            <tr style="<?php echo $inq['status'] === 'new' ? 'font-weight:600;' : ''; ?>">
                <td><?php echo e($inq['name']); ?></td>
                <td><a href="tel:<?php echo e($inq['phone']); ?>"><?php echo e($inq['phone']); ?></a></td>
                <td><?php echo e($inq['service'] ?: 'General'); ?></td>
                <td><?php echo date('d M Y', strtotime($inq['created_at'])); ?></td>
                <td><span class="badge-status badge-<?php echo $inq['status']; ?>"><?php echo ucfirst($inq['status']); ?></span></td>
                <td>
                    <div class="action-btns">
                        <a href="?view=<?php echo $inq['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="?delete=<?php echo $inq['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
