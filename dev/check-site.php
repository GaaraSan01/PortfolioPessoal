<?php
// Self-check do site público (SQLite em memória). Uso: php dev/check-site.php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

define('ROOT', dirname(__DIR__));
require ROOT . '/config/config.php';
require ROOT . '/core/Autoloader.php';
Autoloader::register();
$n = 0;
function check(bool $ok): void { global $n; $n++; if (!$ok) { fwrite(STDERR, "FALHOU: check #$n\n"); exit(1); } }

// Troca o banco do singleton por um SQLite em memória
$pdo = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$db = (new ReflectionClass(Database::class))->newInstanceWithoutConstructor();
(new ReflectionProperty(Database::class, 'pdo'))->setValue($db, $pdo);
(new ReflectionProperty(Database::class, 'instance'))->setValue(null, $db);

$pdo->exec("CREATE TABLE posts (id INTEGER PRIMARY KEY, slug TEXT, title TEXT, excerpt TEXT, category TEXT, content TEXT, date_iso TEXT, status TEXT)");
for ($i = 1; $i <= 8; $i++) {
    $pdo->prepare("INSERT INTO posts (slug, title, content, date_iso, status) VALUES (?, ?, ?, ?, 'published')")
        ->execute(["p$i", "Post $i", $i === 3 ? 'fala de Redis' : 'texto', sprintf('2024-01-%02d', $i)]);
}
$pdo->exec("INSERT INTO posts (slug, title, date_iso, status) VALUES ('rascunho', 'Rascunho', '2025-01-01', 'draft')");

// Expõe os helpers protegidos do Controller
$c = new class extends Controller {
    public function __call($m, $a) { return $this->$m(...$a); }
};

// Rascunho: 404 para visitante, visível para admin com sessão ativa, 404 com sessão expirada
check($c->findPublishedBySlug('posts', 'p1')['title'] === 'Post 1');
check($c->findPublishedBySlug('posts', 'rascunho') === null);
check($c->findPublishedBySlug('posts', 'nao-existe') === null);
$_SESSION = ['admin_logged_in' => true, 'admin_last_seen' => time()];
check($c->findPublishedBySlug('posts', 'rascunho')['title'] === 'Rascunho');
$_SESSION['admin_last_seen'] = time() - ADMIN_IDLE_TIMEOUT - 1;
check($c->findPublishedBySlug('posts', 'rascunho') === null);
$_SESSION = [];

// Paginação no SQL: 8 publicados (rascunho fora), 6 por página, mais novo primeiro
[$rows, $page, $total] = $c->paginate('posts', "status = 'published'", [], 'date_iso DESC', 6);
check(count($rows) === 6 && $page === 1 && $total === 2 && $rows[0]['slug'] === 'p8');
$_GET['page'] = '2';
[$rows, $page] = $c->paginate('posts', "status = 'published'", [], 'date_iso DESC', 6);
check(count($rows) === 2 && $page === 2);
$_GET['page'] = '99'; // página além do fim cai na última
[, $page] = $c->paginate('posts', "status = 'published'", [], 'date_iso DESC', 6);
check($page === 2);
$_GET = [];
[$rows, , $total] = $c->paginate('posts', "status = 'published' AND content LIKE ?", ['%redis%'], 'date_iso DESC', 6);
check(count($rows) === 1 && $rows[0]['slug'] === 'p3' && $total === 1);

// asset(): versão pelo filemtime quando o arquivo existe
check(str_ends_with(asset('css/style.css'), '?v=' . filemtime(ROOT . '/public/assets/css/style.css')));
check(!str_contains(asset('nao/existe.css'), '?v='));

// Índice do post: ids únicos, sem acento, nunca começando por número
[$html, $toc] = BlogController::addHeadingIds('<h2>Introdução</h2><h3>Introdução</h3><h2>2024</h2>');
check(array_column($toc, 'id') === ['introducao', 'introducao-2', 's-2024']);
check(str_contains($html, '<h3 id="introducao-2">'));

echo "OK: $n checks\n";
