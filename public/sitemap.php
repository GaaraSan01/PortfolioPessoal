<?php
// ============================================
// DYNAMIC SITEMAP GENERATOR
// ============================================

require_once __DIR__ . '/../config/config.php';
require_once ROOT . '/core/Database.php';

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// 1. Static Pages
$baseUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$pages = [
    '',
    '/projects',
    '/blog',
    '/contact'
];

foreach ($pages as $page) {
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . $baseUrl . $page . '</loc>' . PHP_EOL;
    echo '    <changefreq>weekly</changefreq>' . PHP_EOL;
    echo '    <priority>' . ($page === '' ? '1.0' : '0.8') . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

try {
    $pdo = Database::getInstance();

    // 2. Projects
    $stmt = $pdo->query("SELECT slug, created_at FROM projects WHERE status = 'published'");
    while ($row = $stmt->fetch()) {
        echo '  <url>' . PHP_EOL;
        echo '    <loc>' . $baseUrl . '/projects/show/' . $row['slug'] . '</loc>' . PHP_EOL;
        echo '    <lastmod>' . date('Y-m-d', strtotime($row['created_at'])) . '</lastmod>' . PHP_EOL;
        echo '    <changefreq>monthly</changefreq>' . PHP_EOL;
        echo '    <priority>0.7</priority>' . PHP_EOL;
        echo '  </url>' . PHP_EOL;
    }

    // 3. Blog Posts
    $stmt = $pdo->query("SELECT slug, date_iso FROM posts WHERE status = 'published'");
    while ($row = $stmt->fetch()) {
        echo '  <url>' . PHP_EOL;
        echo '    <loc>' . $baseUrl . '/blog/show/' . $row['slug'] . '</loc>' . PHP_EOL;
        echo '    <lastmod>' . $row['date_iso'] . '</lastmod>' . PHP_EOL;
        echo '    <changefreq>monthly</changefreq>' . PHP_EOL;
        echo '    <priority>0.6</priority>' . PHP_EOL;
        echo '  </url>' . PHP_EOL;
    }

} catch (Exception $e) {
    // Silently fail if DB is not available
}

echo '</urlset>' . PHP_EOL;
