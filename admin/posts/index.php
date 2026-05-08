<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();
$pageTitle = 'Blog Posts';
$posts     = load_posts();
$flash     = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts — Admin VH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/admin.css">
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="admin-body">
<?php include dirname(__DIR__) . '/partials/sidebar.php'; ?>
<main class="admin-main">
    <?php include dirname(__DIR__) . '/partials/topbar.php'; ?>
    <div class="admin-content">
        <div class="page-header">
            <div>
                <h2>Blog Posts</h2>
                <p><?= count($posts) ?> post(s) cadastrado(s)</p>
            </div>
            <a href="<?= admin_url('posts/edit.php') ?>" class="btn-primary">+ Novo Post</a>
        </div>

        <?php if ($flash): ?>
        <div class="admin-alert <?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
        <div class="empty-state">
            <p>Nenhum post cadastrado ainda.</p>
            <a href="<?= admin_url('posts/edit.php') ?>" class="btn-primary">Criar primeiro post</a>
        </div>
        <?php else: ?>
        <table class="admin-table full">
            <thead>
                <tr><th>Título</th><th>Categoria</th><th>Data</th><th>Leitura</th><th>Status</th><th>Ações</th></tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $p): ?>
            <tr>
                <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
                <td><?= htmlspecialchars($p['date'] ?? '—') ?></td>
                <td><?= (int)($p['reading_time'] ?? 0) ?> min</td>
                <td><span class="badge <?= ($p['status'] ?? 'draft') === 'published' ? 'badge-green' : 'badge-gray' ?>"><?= $p['status'] ?? 'draft' ?></span></td>
                <td class="actions-cell">
                    <a href="<?= admin_url('posts/edit.php?id=' . urlencode($p['id'])) ?>" class="btn-sm">Editar</a>
                    <form method="POST" action="<?= admin_url('posts/delete.php') ?>" onsubmit="return confirm('Excluir este post?')">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <button type="submit" class="btn-sm danger">Excluir</button>
                    </form>
                    <a href="<?= BASE_URL ?>/blog/post/<?= urlencode($p['slug']) ?>" target="_blank" class="btn-sm secondary">Ver ↗</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
