<?php
/**
 * Configurações Globais da Aplicação
 */

function loadEnv($path) {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
    }
}


//define('ROOT', dirname(__DIR__));
// Carrega o ficheiro .env
loadEnv(ROOT . '/.env');

$mailtTo = getenv('EMAIL_TO');
$mailFrom = getenv('EMAIL_FROM');
$chaveKey = getenv('SECRET_KEY');
$baseUrl = getenv('BASE_URL');
$environment = getenv('ENVIRONMENT');


define('APP_NAME', 'Vinicius Henrique Portfolio');

define('BASE_URL', $baseUrl);

define('ENVIRONMENT', $environment);

// Database Config
define('DB_CONNECTION', getenv('DB_CONNECTION') ?: 'sqlite');
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_DATABASE', getenv('DB_DATABASE') ?: 'app/data/database.sqlite');
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');

define('APP_PATH', ROOT . '/app');
define('CORE_PATH', ROOT . '/core');
define('CONFIG_PATH', ROOT . '/config');
define('PUBLIC_PATH', ROOT . '/public');
define('VIEWS_PATH', APP_PATH . '/views');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');


// Email de destino (onde você receberá as mensagens)
define('EMAIL_TO', $mailtTo);

// Email remetente (use um email do seu domínio)
define('EMAIL_FROM', $mailFrom);

// Nome do remetente
define('EMAIL_FROM_NAME', APP_NAME);

// Chave secreta para tokens CSRF
define('SECRET_KEY', $chaveKey . md5(ROOT));

// Rate limiting - tempo entre envios (em segundos)
define('RATE_LIMIT_TIME', 60); // 1 minuto


// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Charset
ini_set('default_charset', 'UTF-8');

// Exibição de erros (apenas em desenvolvimento)
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT . '/error.log');
}


// Configurações seguras de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
} else {
    ini_set('session.cookie_secure', 0); // Garante 0 para localhost/HTTP
}
// ini_set('session.cookie_secure', 1); // Mude para 1 quando usar HTTPS

// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Gera URL completa
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Gera URL de uploads
 */
function uploads($path){
    return BASE_URL . '/public/uploads/' . ltrim($path, '/');
}

/**
 * Gera URL de asset
 */
function asset($path) {
    return BASE_URL . '/public/assets/' . ltrim($path, '/');
}

/**
 * Sanitiza string
 */
function clean($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * Redireciona para URL
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Debug helper
 */
function dd($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

/**
 * Exibe view
 */
function view($viewName, $data = []) {
    $view = new View();
    $view->render($viewName, $data);
}

/**
 * Retorna JSON
 */
function json($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
