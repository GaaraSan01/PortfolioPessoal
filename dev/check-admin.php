<?php
// Self-check dos helpers do admin (SQLite em memória). Uso: php dev/check-admin.php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

require dirname(__DIR__) . '/admin/auth.php';
$n = 0;
function check(bool $ok): void { global $n; $n++; if (!$ok) { fwrite(STDERR, "FALHOU: check #$n\n"); exit(1); } }

$pdo = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec("CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, image TEXT)");

// unique_slug: colisão vira -2, -3; o próprio registro é ignorado
check(unique_slug($pdo, 'projects', 'loja') === 'loja');
$pdo->exec("INSERT INTO projects (slug) VALUES ('loja'), ('loja-2')");
check(unique_slug($pdo, 'projects', 'loja') === 'loja-3');
check(unique_slug($pdo, 'projects', 'loja', 1) === 'loja');
// INSERT sem id funciona (bug original: uniqid() em INTEGER PRIMARY KEY)
$pdo->exec("INSERT INTO projects (slug) VALUES ('novo')");

// format_post_date
check(format_post_date('2024-04-24') === '24 Abr, 2024');
check(format_post_date('2024-02-30') === null);
check(format_post_date('lixo') === null);

// valid_project_url
check(valid_project_url(''));
check(valid_project_url('https://example.com'));
check(!valid_project_url('javascript:alert(1)'));

// limite de login: 5 falhas bloqueiam, sucesso limpa
$ip = '203.0.113.9';
for ($i = 0; $i < 5; $i++) { check(!login_throttled($pdo, $ip)); login_failed($pdo, $ip); }
check(login_throttled($pdo, $ip));
check(!login_throttled($pdo, '198.51.100.1'));
login_succeeded($pdo, $ip);
check(!login_throttled($pdo, $ip));

// Seo::meta: valor do admin > dado do controller > padrão do site; noindex por item ou global
$cache = new ReflectionProperty(Seo::class, 'cache');
$cache->setValue(null, array_merge(Seo::DEFAULTS, ['site.name' => 'VH', 'post.7.title' => 'Título SEO', 'post.8.noindex' => '1']));
$_SERVER['REQUEST_URI'] = '/blog/post/x?utm=1';
$m = Seo::meta(['title' => 'Post | Blog', 'metaDescription' => 'Resumo', 'seoKey' => 'post.7', 'ogType' => 'article']);
check($m['title'] === 'Título SEO | VH');
check($m['description'] === 'Resumo');
check($m['canonical'] === rtrim(BASE_URL, '/') . '/blog/post/x'); // sem query string
check($m['robots'] === 'index, follow' && $m['type'] === 'article');
check(Seo::meta(['seoKey' => 'post.8'])['robots'] === 'noindex, nofollow');
check(Seo::meta(['title' => 'A'])['description'] === Seo::DEFAULTS['site.description']);
$cache->setValue(null, array_merge(Seo::DEFAULTS, ['site.indexing' => '0']));
check(Seo::meta(['title' => 'A'])['robots'] === 'noindex, nofollow');
check(strpos(Seo::personJsonLd(), '</') === false); // JSON_HEX_TAG impede fechar o <script>

// Seo::issues
check(Seo::issues('Curto', '') === ['Sem descrição']);
check(Seo::issues(str_repeat('a', 61), str_repeat('d', 80), '') === ['Título longo (61)', 'Sem imagem']);
check(Seo::issues('Ok', str_repeat('d', 80)) === []);

echo "OK ($n checks)\n";
