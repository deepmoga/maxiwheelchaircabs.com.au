<?php
session_start();

// Load credentials from config.env.php (not committed to git)
if (file_exists(__DIR__ . '/config.env.php')) {
    require_once __DIR__ . '/config.env.php';
} else {
    // Default/fallback values
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'maxiwheelchaircabs');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('SITE_URL', 'http://localhost');
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        header('Location: install.php');
        exit;
    }
    die('Database connection failed.');
}

function getSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

function getAllSettings() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (Exception $e) {
        return [];
    }
}

function getActiveServices() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM services WHERE status = 1 ORDER BY sort_order ASC");
    return $stmt->fetchAll();
}

function getServiceBySlug($slug) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM services WHERE slug = ? AND status = 1");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getActiveTestimonials() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order ASC LIMIT 6");
    return $stmt->fetchAll();
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
