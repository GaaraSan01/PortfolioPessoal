<?php
/**
 * Seed de desenvolvimento: recria as tabelas e insere dados fictícios.
 * Uso: php dev/seed.php   (APAGA posts, projects e users do banco configurado)
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';

if (ENVIRONMENT === 'production') exit("Recusado: ENVIRONMENT=production\n");

$pdo = Database::getInstance();

// Colunas conforme usadas pelos controllers/admin (iguais a sql/create_*.sql)
$pdo->exec("
DROP TABLE IF EXISTS posts; DROP TABLE IF EXISTS projects; DROP TABLE IF EXISTS users;
CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT NOT NULL UNIQUE, title TEXT NOT NULL,
    date TEXT NOT NULL, date_iso DATE, category TEXT, excerpt TEXT, content TEXT,
    reading_time INTEGER, status TEXT DEFAULT 'draft', created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT NOT NULL UNIQUE, title TEXT NOT NULL,
    category TEXT, year TEXT, image TEXT, description TEXT, long_description TEXT,
    technologies TEXT, url TEXT, status TEXT DEFAULT 'draft', created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, password_hash TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);");

// Credenciais só de desenvolvimento local (nunca usar em produção)
$pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)")
    ->execute(['root', password_hash('root2026', PASSWORD_ARGON2ID)]);

$projects = [
    ['loja-aurora', 'Loja Aurora', 'E-commerce', '2026', 'Loja virtual de cosméticos com checkout em uma página.', ['PHP', 'MySQL', 'JavaScript'], 'published'],
    ['painel-frota', 'Painel Frota+', 'Dashboard', '2026', 'Dashboard de rastreamento de veículos em tempo real.', ['React', 'Node.js', 'WebSocket'], 'published'],
    ['clinica-vida', 'Clínica Vida', 'Website', '2025', 'Site institucional com agendamento online de consultas.', ['PHP', 'SQLite', 'CSS3'], 'published'],
    ['app-receitas', 'App Receitas', 'Mobile', '2025', 'Aplicativo de receitas com lista de compras automática.', ['Flutter', 'Firebase'], 'published'],
    ['crm-vendas', 'CRM Vendas', 'Sistema', '2025', 'CRM simples para pequenas equipes comerciais.', ['Laravel', 'Vue.js', 'MySQL'], 'published'],
    ['landing-eventos', 'Landing Eventos', 'Landing Page', '2024', 'Landing page de inscrição para conferência de tecnologia.', ['HTML', 'CSS3', 'JavaScript'], 'published'],
    ['api-estoque', 'API Estoque', 'Backend', '2024', 'API REST para controle de estoque multiloja.', ['PHP', 'Docker', 'MySQL'], 'published'],
    ['blog-viagens', 'Blog Viagens', 'Website', '2024', 'Blog de viagens com mapa interativo de destinos.', ['WordPress', 'Leaflet'], 'draft'],
];
$stmt = $pdo->prepare("INSERT INTO projects (slug, title, category, year, image, description, long_description, technologies, url, status, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
foreach ($projects as $i => [$slug, $title, $cat, $year, $desc, $tech, $status]) {
    $stmt->execute([
        $slug, $title, $cat, $year,
        "https://picsum.photos/seed/$slug/1200/800",
        $desc,
        "$desc\n\nProjeto fictício para testes locais. Inclui levantamento de requisitos, design de interface, desenvolvimento e deploy.",
        json_encode($tech),
        "https://example.com/$slug",
        $status,
        date('Y-m-d H:i:s', strtotime("-$i weeks")),
    ]);
}

$posts = [
    ['mvc-do-zero-em-php', 'Construindo um MVC do zero em PHP', 'PHP'],
    ['sqlite-em-producao', 'SQLite em produção: vale a pena?', 'Banco de Dados'],
    ['css-grid-na-pratica', 'CSS Grid na prática', 'Front-end'],
    ['seguranca-em-formularios', 'Segurança em formulários: CSRF e honeypot', 'Segurança'],
    ['docker-para-devs-php', 'Docker para desenvolvedores PHP', 'DevOps'],
    ['markdown-no-blog', 'Renderizando Markdown com Parsedown', 'PHP'],
    ['performance-web-basica', 'Performance web: o básico que funciona', 'Front-end'],
    ['rascunho-ideias', 'Rascunho: ideias para próximos posts', 'Geral'],
];
$months = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
$stmt = $pdo->prepare("INSERT INTO posts (slug, title, date, date_iso, category, excerpt, content, reading_time, status) VALUES (?,?,?,?,?,?,?,?,?)");
foreach ($posts as $i => [$slug, $title, $cat]) {
    $ts = strtotime('-' . ($i * 12) . ' days');
    $stmt->execute([
        $slug, $title,
        date('d', $ts) . ' ' . $months[date('n', $ts) - 1] . ' ' . date('Y', $ts),
        date('Y-m-d', $ts),
        $cat,
        "Um resumo fictício sobre \"$title\" para testar a listagem do blog.",
        "## Introdução\n\nTexto fictício sobre **$title**.\n\n- Ponto um\n- Ponto dois\n\n```php\necho 'Olá, mundo!';\n```\n\n## Conclusão\n\nFim do post de teste.",
        3 + $i,
        $slug === 'rascunho-ideias' ? 'draft' : 'published',
    ]);
}

echo "Seed OK: " . count($projects) . " projetos, " . count($posts) . " posts, usuário admin 'root'.\n";
