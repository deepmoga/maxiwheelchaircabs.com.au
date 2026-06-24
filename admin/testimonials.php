<?php
require_once 'includes/auth.php';
requireLogin();

$success = '';

// Delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$_GET['delete']]);
    header('Location: testimonials.php?msg=deleted');
    exit;
}

// Toggle
if (isset($_GET['toggle'])) {
    $pdo->prepare("UPDATE testimonials SET status = IF(status=1,0,1) WHERE id = ?")->execute([$_GET['toggle']]);
    header('Location: testimonials.php');
    exit;
}

// Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = $_POST['id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $review = trim($_POST['review'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($name && $review) {
        if ($tid) {
            $pdo->prepare("UPDATE testimonials SET name=?, location=?, review=?, rating=?, sort_order=? WHERE id=?")->execute([$name, $location, $review, $rating, $sort_order, $tid]);
        } else {
            $pdo->prepare("INSERT INTO testimonials (name, location, review, rating, sort_order) VALUES (?, ?, ?, ?, ?)")->execute([$name, $location, $review, $rating, $sort_order]);
        }
        header('Location: testimonials.php?msg=saved');
        exit;
    }
}

if (isset($_GET['msg'])) $success = 'Testimonial ' . $_GET['msg'] . ' successfully!';

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll();
$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch();
}

include 'includes/header.php';
?>

<div class="page-title"><h2>Testimonials</h2></div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show"><?php echo e($success); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4">
        <div class="admin-card">
            <h3><?php echo $editItem ? 'Edit Testimonial' : 'Add Testimonial'; ?></h3>
            <form method="POST">
                <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($editItem['name'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?php echo e($editItem['location'] ?? ''); ?>" placeholder="e.g. Fremantle, WA">
                </div>
                <div class="mb-3">
                    <label class="form-label">Review *</label>
                    <textarea name="review" class="form-control" rows="4" required><?php echo e($editItem['review'] ?? ''); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <?php for ($r = 5; $r >= 1; $r--): ?>
                            <option value="<?php echo $r; ?>" <?php echo ($editItem['rating'] ?? 5) == $r ? 'selected' : ''; ?>><?php echo $r; ?> Stars</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo $editItem['sort_order'] ?? 0; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-admin w-100 mt-3"><i class="fas fa-save"></i> Save</button>
                <?php if ($editItem): ?>
                <a href="testimonials.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <h3>All Testimonials</h3>
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Review</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($testimonials as $t): ?>
                    <tr>
                        <td><strong><?php echo e($t['name']); ?></strong><br><small><?php echo e($t['location']); ?></small></td>
                        <td style="max-width:300px;"><?php echo e(substr($t['review'], 0, 100)); ?>...</td>
                        <td><?php for ($i=0; $i<$t['rating']; $i++) echo '<i class="fas fa-star" style="color:#FFC107;font-size:11px;"></i>'; ?></td>
                        <td><a href="?toggle=<?php echo $t['id']; ?>"><span class="badge-status <?php echo $t['status'] ? 'badge-active' : 'badge-inactive'; ?>"><?php echo $t['status'] ? 'Active' : 'Hidden'; ?></span></a></td>
                        <td>
                            <div class="action-btns">
                                <a href="?edit=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
