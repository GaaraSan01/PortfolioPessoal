<?php
// ============================================
// ROBOTS.TXT DINÂMICO (servido em /robots.txt)
// A indexação liga/desliga em Admin → SEO → Configurações.
// ============================================

define('ROOT', __DIR__);
require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Seo.php';

header('Content-Type: text/plain; charset=utf-8');

$baseUrl = rtrim(BASE_URL ?: (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), '/');

echo "User-agent: *\n";

if (!Seo::indexingEnabled()) {
    echo "Disallow: /\n";
    exit;
}

echo "Allow: /\n\n";
echo "# Painel administrativo e código da aplicação\n";
foreach (['/admin/', '/app/', '/config/', '/core/', '/vendor/'] as $path) {
    echo "Disallow: $path\n";
}
echo "\nSitemap: $baseUrl/sitemap.xml\n";
