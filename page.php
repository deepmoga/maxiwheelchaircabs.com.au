<?php
require_once 'config.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? AND status = 1");
$stmt->execute([$slug]);
$page = $stmt->fetch();

if (!$page) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'Page Not Found';
    include 'includes/header.php';
    echo '<section class="page-banner"><div class="container"><h1>Page Not Found</h1></div></section>';
    echo '<section class="section"><div class="container text-center"><p>The page you are looking for does not exist.</p><a href="' . SITE_URL . '/" class="btn btn-primary">Go Home</a></div></section>';
    include 'includes/footer.php';
    exit;
}

$page_title = $page['meta_title'] ?: $page['title'];
$meta_description = $page['meta_description'] ?? '';
$meta_keywords = $page['meta_keywords'] ?? '';

include 'includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1><?php echo e($page['title']); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo $base_url; ?>/">Home</a>
            <span>/</span>
            <span><?php echo e($page['title']); ?></span>
        </div>
    </div>
</section>

<section class="about-page-content">
    <div class="container">
        <div class="content-area">
            <?php echo $page['content']; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
