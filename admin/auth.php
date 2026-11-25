<?php

define('ADMIN_PATH', __DIR__);
define('ROOT', dirname(__DIR__));

// Carregar config global do site para usar constantes (BASE_URL, etc.)
require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';

// Credenciais agora são validadas através do banco de dados (tabela users)

const ADMIN_IDLE_TIMEOUT = 7200; // 2h sem atividade encerra a sessão
const LOGIN_MAX_ATTEMPTS = 5;
const LOGIN_WINDOW = 900; // 15 min

function admin_auth_check() {
    $expired = isset($_SESSION['admin_last_seen']) && time() - $_SESSION['admin_last_seen'] > ADMIN_IDLE_TIMEOUT;
    if ($expired) {
        session_destroy();
    }
    if ($expired || empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . BASE_URL . '/admin/index.php');
        exit;
    }
    $_SESSION['admin_last_seen'] = time();
}

// Tentativas ficam no banco: um limite na sessão some ao descartar o cookie.
function login_throttled(PDO $pdo, string $ip): bool {
    $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (ip VARCHAR(64) NOT NULL, attempted_at INTEGER NOT NULL)");
    $pdo->prepare("DELETE FROM login_attempts WHERE attempted_at < ?")->execute([time() - LOGIN_WINDOW]);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip = ?");
    $stmt->execute([$ip]);
    return (int)$stmt->fetchColumn() >= LOGIN_MAX_ATTEMPTS;
}

function login_failed(PDO $pdo, string $ip): void {
    $pdo->prepare("INSERT INTO login_attempts (ip, attempted_at) VALUES (?, ?)")->execute([$ip, time()]);
}

function login_succeeded(PDO $pdo, string $ip): void {
    $pdo->prepare("DELETE FROM login_attempts WHERE ip = ?")->execute([$ip]);
}

function unique_slug(PDO $pdo, string $table, string $base, ?int $ignoreId = null): string {
    if (!in_array($table, ['projects', 'posts'], true)) {
        throw new InvalidArgumentException('Tabela inválida');
    }
    $base = $base !== '' ? $base : 'item';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE slug = ? AND id != ?");
    for ($slug = $base, $n = 2; ; $slug = "$base-" . $n++) {
        $stmt->execute([$slug, $ignoreId ?? 0]);
        if ((int)$stmt->fetchColumn() === 0) return $slug;
    }
}

// Apaga só arquivos locais dentro de UPLOADS_PATH que nenhum projeto ainda usa; URLs externas são ignoradas.
function delete_upload(PDO $pdo, ?string $image): void {
    if (!$image || filter_var($image, FILTER_VALIDATE_URL)) return;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE image = ?");
    $stmt->execute([$image]);
    if ((int)$stmt->fetchColumn() > 0) return;
    $file = UPLOADS_PATH . '/' . basename($image);
    if (is_file($file)) unlink($file);
}

function format_post_date(string $iso): ?string {
    $d = DateTime::createFromFormat('!Y-m-d', $iso);
    if (!$d || $d->format('Y-m-d') !== $iso) return null;
    $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    return $d->format('d') . ' ' . $months[(int)$d->format('n') - 1] . ', ' . $d->format('Y');
}

function valid_project_url(string $url): bool {
    return $url === '' || (filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true));
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
