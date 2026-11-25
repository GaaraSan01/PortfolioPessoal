<?php
// Router do servidor embutido (substitui o .htaccess): php -S localhost:8000 dev/router.php
// Mesma CSP do .htaccess, para testá-la também no servidor embutido
if (preg_match('/Content-Security-Policy "([^"]+)"/', file_get_contents(dirname(__DIR__) . '/.htaccess'), $m)) header('Content-Security-Policy: ' . $m[1]);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path !== '/' && is_file(dirname(__DIR__) . $path)) return false;
if (str_starts_with($path, '/admin')) return false;
if ($path === '/sitemap.xml') { require dirname(__DIR__) . '/sitemap.php'; return; }
if ($path === '/robots.txt') { require dirname(__DIR__) . '/robots.php'; return; }
if ($path !== '/') $_GET['url'] = ltrim($path, '/'); // "/" não é reescrito pelo .htaccess
require dirname(__DIR__) . '/index.php';
