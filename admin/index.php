<?php
require_once 'includes/auth.php';
requireLogin();

$totalServices = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalTestimonials = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
$totalInquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$newInquiries = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$recentInquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();

include 'includes/header.php';
?>

<div class="page-title">
    <h2>Dashboard</h2>
    <span style="font-size:13px;color:var(--admin-text-light);">Welcome back, Admin</span>
</div>

<div class="dash-stats">
    <div class="dash-stat">
        <div class="dash-stat-icon yellow"><i class="fas fa-concierge-bell"></i></div>
        <div class="dash-stat-info"><h4><?php echo $totalServices; ?></h4><p>Active Services</p></div>
    </div>
    <div class="dash-stat">
        <div class="dash-stat-icon blue"><i class="fas fa-envelope"></i></div>
        <div class="dash-stat-info"><h4><?php echo $totalInquiries; ?></h4><p>Total Inquiries</p></div>
    </div>
    <div class="dash-stat">
        <div class="dash-stat-icon green"><i class="fas fa-star"></i></div>
        <div class="dash-stat-info"><h4><?php echo $totalTestimonials; ?></h4><p>Testimonials</p></div>
    </div>
    <div class="dash-stat">
        <div class="dash-stat-icon red"><i class="fas fa-bell"></i></div>
        <div class="dash-stat-info"><h4><?php echo $newInquiries; ?></h4><p>New Inquiries</p></div>
    </div>
</div>

<div class="admin-card">
    <h3>Recent Inquiries</h3>
    <?php if (empty($recentInquiries)): ?>
    <p style="color:var(--admin-text-light);">No inquiries yet.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr><th>Name</th><th>Phone</th><th>Service</th><th>Date</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach ($recentInquiries as $inq): ?>
            <tr>
                <td><strong><?php echo e($inq['name']); ?></strong></td>
                <td><?php echo e($inq['phone']); ?></td>
                <td><?php echo e($inq['service'] ?: 'General'); ?></td>
                <td><?php echo date('d M Y, H:i', strtotime($inq['created_at'])); ?></td>
                <td><span class="badge-status badge-<?php echo $inq['status']; ?>"><?php echo ucfirst($inq['status']); ?></span></td>
                <td><a href="inquiries.php?view=<?php echo $inq['id']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
