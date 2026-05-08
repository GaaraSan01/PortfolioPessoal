<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();
$pageTitle = 'Projetos';
$projects  = load_projects();
$flash     = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos — Admin VH</title>
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
                <h2>Projetos</h2>
                <p><?= count($projects) ?> projeto(s) cadastrado(s)</p>
            </div>
            <a href="<?= admin_url('projects/edit.php') ?>" class="btn-primary">+ Novo Projeto</a>
        </div>

        <?php if ($flash): ?>
        <div class="admin-alert <?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
        <?php endif; ?>

        <?php if (empty($projects)): ?>
        <div class="empty-state">
            <p>Nenhum projeto cadastrado ainda.</p>
            <a href="<?= admin_url('projects/edit.php') ?>" class="btn-primary">Criar primeiro projeto</a>
        </div>
        <?php else: ?>
        <table class="admin-table full">
            <thead>
                <tr><th>Título</th><th>Categoria</th><th>Ano</th><th>Status</th><th>Ações</th></tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
            <tr>
                <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
                <td><?= htmlspecialchars($p['year'] ?? '—') ?></td>
                <td><span class="badge <?= ($p['status'] ?? 'draft') === 'published' ? 'badge-green' : 'badge-gray' ?>"><?= $p['status'] ?? 'draft' ?></span></td>
                <td class="actions-cell">
                    <a href="<?= admin_url('projects/edit.php?id=' . urlencode($p['id'])) ?>" class="btn-sm">Editar</a>
                    <form method="POST" action="<?= admin_url('projects/delete.php') ?>" onsubmit="return confirm('Excluir este projeto?')">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <button type="submit" class="btn-sm danger">Excluir</button>
                    </form>
                    <a href="<?= BASE_URL ?>/projects/show/<?= urlencode($p['slug']) ?>" target="_blank" class="btn-sm secondary">Ver ↗</a>
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
