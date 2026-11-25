<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();

$siteSuffix = ' | ' . Seo::get('site.name', APP_NAME);
$rows = [];

// Mesmos títulos/descrições que o site público monta (ver Seo::meta e os controllers)
foreach (load_projects() as $p) {
    if (($p['status'] ?? '') !== 'published') continue;
    $k = "project.{$p['id']}";
    $title = (Seo::get("$k.title") ?: $p['title'] . ' | Projetos') . $siteSuffix;
    $desc = Seo::get("$k.description") ?: trim($p['description'] ?? '');
    $rows[] = ['type' => 'Projeto', 'title' => $p['title'], 'edit' => admin_url('projects/edit.php?id=' . urlencode($p['id'])),
        'noindex' => Seo::get("$k.noindex") === '1', 'issues' => Seo::issues($title, $desc, trim($p['image'] ?? ''))];
}
foreach (load_posts() as $p) {
    if (($p['status'] ?? '') !== 'published') continue;
    $k = "post.{$p['id']}";
    $title = (Seo::get("$k.title") ?: $p['title'] . ' | Blog') . $siteSuffix;
    $desc = Seo::get("$k.description") ?: trim($p['excerpt'] ?? '');
    $rows[] = ['type' => 'Post', 'title' => $p['title'], 'edit' => admin_url('posts/edit.php?id=' . urlencode($p['id'])),
        'noindex' => Seo::get("$k.noindex") === '1', 'issues' => Seo::issues($title, $desc)];
}

$withIssues = count(array_filter($rows, fn($r) => $r['issues']));
// Itens com problema primeiro
usort($rows, fn($a, $b) => count($b['issues']) <=> count($a['issues']));

$globalIssues = [];
if (!Seo::indexingEnabled()) $globalIssues[] = 'A indexação está desligada: nenhuma página aparece nos buscadores.';
if (!Seo::get('site.google_verification')) $globalIssues[] = 'O Google Search Console ainda não foi verificado.';
if (!BASE_URL) $globalIssues[] = 'BASE_URL não está definida no .env: canonical e sitemap ficam sem domínio.';

$pageTitle  = 'Diagnóstico de SEO';
$page_title = $pageTitle . ' — Admin VH';
include dirname(__DIR__) . '/partials/header.php';
?>

<div class="page-header">
    <div>
        <h2><?= htmlspecialchars($pageTitle) ?></h2>
        <p>Conteúdo publicado com problemas que prejudicam a aparência no Google.</p>
    </div>
    <a href="<?= admin_url('seo/settings.php') ?>" class="btn-primary">Configurações de SEO</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-number"><?= Seo::indexingEnabled() ? 'Ligada' : 'Desligada' ?></span>
            <span class="stat-label">Indexação</span>
            <span class="stat-sub"><?= Seo::indexingEnabled() ? 'Visível nos buscadores' : 'Site oculto (noindex)' ?></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-number"><?= $withIssues ?> / <?= count($rows) ?></span>
            <span class="stat-label">Itens com alertas</span>
            <span class="stat-sub">Projetos e posts publicados</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-number">
                <a href="<?= BASE_URL ?>/sitemap.xml" target="_blank" rel="noopener" style="color:inherit;">sitemap.xml ↗</a>
            </span>
            <span class="stat-label">Arquivos para buscadores</span>
            <span class="stat-sub"><a href="<?= BASE_URL ?>/robots.txt" target="_blank" rel="noopener">robots.txt ↗</a></span>
        </div>
    </div>
</div>

<?php if ($globalIssues): ?>
<ul class="seo-checklist">
    <?php foreach ($globalIssues as $issue): ?>
    <li><span class="badge badge-warn">Atenção</span> <?= htmlspecialchars($issue) ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (empty($rows)): ?>
<div class="empty-state">
    <p>Nenhum projeto ou post publicado ainda.</p>
</div>
<?php else: ?>
<table class="admin-table full">
    <thead>
        <tr><th>Conteúdo</th><th>Tipo</th><th>Situação</th><th>Ações</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><strong><?= htmlspecialchars($r['title']) ?></strong></td>
        <td><?= $r['type'] ?></td>
        <td><div class="issues-list">
            <?php if ($r['noindex']): ?><span class="badge badge-gray">noindex</span><?php endif; ?>
            <?php foreach ($r['issues'] as $issue): ?>
                <span class="badge badge-warn"><?= htmlspecialchars($issue) ?></span>
            <?php endforeach; ?>
            <?php if (!$r['issues']): ?><span class="badge badge-green">OK</span><?php endif; ?>
        </div></td>
        <td class="actions-cell"><a href="<?= htmlspecialchars($r['edit']) ?>" class="btn-sm">Editar</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
