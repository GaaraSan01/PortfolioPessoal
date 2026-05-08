<?php
// =============================================
// ADMIN GUARD — inclua no topo de cada página
// =============================================

define('ADMIN_PATH', __DIR__);
define('ROOT', dirname(__DIR__));

// Carregar config global do site para usar constantes (BASE_URL, etc.)
require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';

// ─── CREDENCIAIS (altere aqui ou mova para .env) ───────────────────
define('ADMIN_USER',     getenv('ADMIN_USER')     ?: 'admin');
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: 'portfolio2026');
// ───────────────────────────────────────────────────────────────────

function admin_auth_check() {
    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . BASE_URL . '/admin/index.php');
        exit;
    }
}

function admin_url($path = '') {
    return BASE_URL . '/admin/' . ltrim($path, '/');
}

function data_path($file) {
    return ROOT . '/app/data/' . $file;
}

// Helpers de dados PDO
function load_projects() {
    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = $stmt->fetchAll();
        foreach ($projects as &$p) {
            $p['technologies'] = json_decode($p['technologies'] ?? '[]', true) ?: [];
        }
        return $projects;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

function load_posts() {
    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY date_iso DESC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

function generate_slug(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[àáâãäå]/u', 'a', $text);
    $text = preg_replace('/[èéêë]/u', 'e', $text);
    $text = preg_replace('/[ìíîï]/u', 'i', $text);
    $text = preg_replace('/[òóôõö]/u', 'o', $text);
    $text = preg_replace('/[ùúûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[ñ]/u', 'n', $text);
    $text = preg_replace('/[^a-z0-9\s-]/u', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function csrf_token(): string {
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf'];
}

function csrf_check(): bool {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['admin_csrf'] ?? '', $_POST['csrf_token']);
}
