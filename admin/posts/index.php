<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();
$pageTitle = 'Blog Posts';
$posts     = load_posts();
$flash     = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
?>
<?php
$page_title = 'Posts — Admin VH';
$body_class = 'admin-body';
include dirname(__DIR__) . '/partials/header.php';
?>

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

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>

