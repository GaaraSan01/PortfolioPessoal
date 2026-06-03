<?php
require_once __DIR__ . '/auth.php';
admin_auth_check();

$projects = load_projects();
$posts    = load_posts();
$pub_proj = count(array_filter($projects, fn($p) => ($p['status'] ?? '') === 'published'));
$pub_post = count(array_filter($posts, fn($p) => ($p['status'] ?? '') === 'published'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Admin VH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= uploads('favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= uploads('favicon/favicon-16x16.png') ?>">
    <link rel="shortcut icon" href="<?= uploads('favicon/favicon.ico') ?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= uploads('favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= uploads('favicon/site.webmanifest') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/admin.css">
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="admin-body">

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="admin-main">
    <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="admin-content">
        <div class="page-header">
            <h2>Dashboard</h2>
            <p>Bem-vindo de volta, <strong><?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?></strong>.</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon projects-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= count($projects) ?></span>
                    <span class="stat-label">Projetos Total</span>
                    <span class="stat-sub"><?= $pub_proj ?> publicados</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon posts-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= count($posts) ?></span>
                    <span class="stat-label">Posts Total</span>
                    <span class="stat-sub"><?= $pub_post ?> publicados</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon site-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-number">
                        <a href="<?= BASE_URL ?>" target="_blank" style="color:inherit;">Ver Site</a>
                    </span>
                    <span class="stat-label">Portfólio</span>
                    <span class="stat-sub">Abrir em nova aba</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Ações Rápidas</h3>
            <div class="actions-grid">
                <a href="<?= admin_url('projects/edit.php') ?>" class="action-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Novo Projeto
                </a>
                <a href="<?= admin_url('posts/edit.php') ?>" class="action-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Novo Post
                </a>
                <a href="<?= admin_url('projects/index.php') ?>" class="action-card secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                    Gerenciar Projetos
                </a>
                <a href="<?= admin_url('posts/index.php') ?>" class="action-card secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                    Gerenciar Posts
                </a>
            </div>
        </div>

        <!-- Últimos projetos e posts -->
        <div class="recent-grid">
            <div class="recent-block">
                <div class="recent-header">
                    <h3>Projetos Recentes</h3>
                    <a href="<?= admin_url('projects/index.php') ?>">Ver todos</a>
                </div>
                <table class="admin-table">
                    <thead><tr><th>Título</th><th>Categoria</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach (array_slice($projects, 0, 5) as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
                        <td><span class="badge <?= ($p['status'] ?? 'draft') === 'published' ? 'badge-green' : 'badge-gray' ?>"><?= $p['status'] ?? 'draft' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="recent-block">
                <div class="recent-header">
                    <h3>Posts Recentes</h3>
                    <a href="<?= admin_url('posts/index.php') ?>">Ver todos</a>
                </div>
                <table class="admin-table">
                    <thead><tr><th>Título</th><th>Categoria</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach (array_slice($posts, 0, 5) as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
                        <td><span class="badge <?= ($p['status'] ?? 'draft') === 'published' ? 'badge-green' : 'badge-gray' ?>"><?= $p['status'] ?? 'draft' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</body>
</html>
