<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();
$pageTitle = 'Projetos';
$projects  = load_projects();
$flash     = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
?>
<?php
$page_title = 'Projetos — Admin VH';
$body_class = 'admin-body';
include dirname(__DIR__) . '/partials/header.php';
?>

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

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>

