<?php
// ============================================
// DYNAMIC SITEMAP GENERATOR
// ============================================

define('ROOT', __DIR__);
require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Seo.php';

header("Content-Type: application/xml; charset=utf-8");

$baseUrl = rtrim(BASE_URL ?: (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), '/');

function sitemap_url(string $loc, ?string $lastmod, string $changefreq, string $priority): void
{
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc>' . PHP_EOL;
    if ($lastmod) echo '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
    echo '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
    echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// 1. Static Pages
foreach (['', '/about', '/projects', '/blog', '/contact'] as $page) {
    sitemap_url($baseUrl . $page, null, 'weekly', $page === '' ? '1.0' : '0.8');
}

try {
    $pdo = Database::getInstance();
    // Itens marcados como noindex no admin ficam fora do sitemap
    $hidden = fn(string $key) => Seo::get("$key.noindex") === '1';

    // 2. Projects
    $stmt = $pdo->query("SELECT id, slug, created_at FROM projects WHERE status = 'published'");
    while ($row = $stmt->fetch()) {
        if ($hidden("project.{$row['id']}")) continue;
        sitemap_url($baseUrl . '/projects/show/' . $row['slug'], date('Y-m-d', strtotime($row['created_at'])), 'monthly', '0.7');
    }

    // 3. Blog Posts
    $stmt = $pdo->query("SELECT id, slug, date_iso FROM posts WHERE status = 'published'");
    while ($row = $stmt->fetch()) {
        if ($hidden("post.{$row['id']}")) continue;
        sitemap_url($baseUrl . '/blog/post/' . $row['slug'], $row['date_iso'] ?: null, 'monthly', '0.6');
    }

} catch (Exception $e) {
    // Silently fail if DB is not available
}

echo '</urlset>' . PHP_EOL;
