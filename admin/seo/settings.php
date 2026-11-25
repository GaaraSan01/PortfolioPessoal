<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();

$errors = [];
$flash  = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);

$textFields = ['site.name', 'site.description', 'site.og_image', 'person.name', 'person.job_title', 'person.description', 'person.same_as'];
foreach (array_keys(Seo::PAGES) as $page) {
    $textFields[] = "page.$page.title";
    $textFields[] = "page.$page.description";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Token inválido. Tente novamente.';
    } else {
        $in = $_POST['seo'] ?? [];
        $values = [];
        foreach ($textFields as $f) {
            $values[$f] = trim((string)($in[$f] ?? ''));
        }
        // '0' explícito: vazio voltaria ao padrão (indexação ligada)
        $values['site.indexing'] = !empty($in['site.indexing']) ? '1' : '0';

        // Aceita o código puro ou a tag <meta ... content="..."> colada inteira
        foreach (['site.google_verification', 'site.bing_verification'] as $f) {
            $code = trim((string)($in[$f] ?? ''));
            if (preg_match('/content=["\']([^"\']+)["\']/', $code, $m)) $code = $m[1];
            if ($code !== '' && !preg_match('/^[A-Za-z0-9_\-=]+$/', $code)) {
                $errors[] = 'Código de verificação inválido.';
            }
            $values[$f] = $code;
        }

        foreach (array_filter(array_map('trim', explode("\n", $values['person.same_as']))) as $link) {
            if (!valid_project_url($link)) $errors[] = 'Link de perfil inválido: ' . $link;
        }

        if (empty($errors) && ($file = upload_image('og_image_file', 'og_', $errors))) {
            $values['site.og_image'] = $file;
        }

        if (empty($errors)) {
            try {
                Seo::save($values);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Configurações de SEO salvas.'];
                header('Location: ' . admin_url('seo/settings.php'));
                exit;
            } catch (Exception $e) {
                error_log('Admin seo save: ' . $e->getMessage());
                $errors[] = 'Erro ao salvar no banco de dados.';
            }
        }
    }
}

// Em erro, mantém o que foi digitado
$val = fn(string $f) => $_SERVER['REQUEST_METHOD'] === 'POST' ? (string)($_POST['seo'][$f] ?? '') : Seo::get($f);
$indexing = $_SERVER['REQUEST_METHOD'] === 'POST' ? !empty($_POST['seo']['site.indexing']) : Seo::indexingEnabled();
$ogImage = Seo::imageUrl(Seo::get('site.og_image'));
$siteSuffix = ' | ' . Seo::get('site.name', APP_NAME);

$pageTitle  = 'Configurações de SEO';
$page_title = $pageTitle . ' — Admin VH';
include dirname(__DIR__) . '/partials/header.php';
?>

<div class="page-header">
    <div>
        <a href="<?= admin_url('seo/index.php') ?>" class="back-link-admin">← Diagnóstico de SEO</a>
        <h2><?= htmlspecialchars($pageTitle) ?></h2>
        <p>Valores padrão usados em todas as páginas do site.</p>
    </div>
</div>

<?php if ($flash): ?>
    <div class="admin-alert <?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['msg']) ?></div>
<?php endif; ?>
<?php if ($errors): ?>
    <div class="admin-alert error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<form method="POST" class="admin-form" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <fieldset class="form-section">
        <legend class="form-section-title">Geral</legend>

        <label class="form-check">
            <input type="checkbox" name="seo[site.indexing]" value="1" <?= $indexing ? 'checked' : '' ?>>
            <span>Permitir que buscadores indexem o site <small>(desmarque em ambientes de teste: o site inteiro recebe noindex e o robots.txt bloqueia tudo)</small></span>
        </label>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="site-name">Nome do site</label>
                <input type="text" id="site-name" name="seo[site.name]" value="<?= htmlspecialchars($val('site.name')) ?>" placeholder="<?= htmlspecialchars(APP_NAME) ?>">
                <small>Aparece no fim de todo título: “Página | Nome do site”.</small>
            </div>
            <div class="form-group">
                <label for="site-google">Verificação do Google Search Console</label>
                <input type="text" id="site-google" name="seo[site.google_verification]" value="<?= htmlspecialchars($val('site.google_verification')) ?>" placeholder="Cole o código ou a tag <meta>">
                <small>Opção “Tag HTML” no Search Console.</small>
            </div>
        </div>

        <div class="form-group">
            <label for="site-description">Descrição padrão</label>
            <textarea id="site-description" name="seo[site.description]" rows="2" data-counter="<?= Seo::DESCRIPTION_MAX ?>"><?= htmlspecialchars($val('site.description')) ?></textarea>
            <small>Usada quando uma página não tem descrição própria.</small>
        </div>

        <div class="form-group">
            <label for="site-bing">Verificação do Bing Webmaster Tools</label>
            <input type="text" id="site-bing" name="seo[site.bing_verification]" value="<?= htmlspecialchars($val('site.bing_verification')) ?>" placeholder="Opcional">
        </div>
    </fieldset>

    <fieldset class="form-section">
        <legend class="form-section-title">Imagem de compartilhamento</legend>
        <p class="form-section-hint">Aparece ao compartilhar links no WhatsApp, LinkedIn e redes sociais. Projetos usam a própria imagem.</p>
        <div class="og-field">
            <div class="og-preview">
                <?php if ($ogImage): ?>
                    <img src="<?= htmlspecialchars($ogImage) ?>" alt="Imagem de compartilhamento atual">
                <?php else: ?>
                    <span>Sem imagem</span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="site-og">Arquivo ou URL</label>
                <input type="text" id="site-og" name="seo[site.og_image]" value="<?= htmlspecialchars($val('site.og_image')) ?>" style="margin-bottom: 8px;">
                <input type="file" name="og_image_file" accept="image/*" aria-label="Enviar nova imagem de compartilhamento">
                <small>Recomendado: 1200 × 630 px, JPG ou PNG.</small>
            </div>
        </div>
    </fieldset>

    <fieldset class="form-section">
        <legend class="form-section-title">Identidade (Schema.org)</legend>
        <p class="form-section-hint">Dados estruturados que ajudam o Google a mostrar seu perfil nos resultados.</p>
        <div class="form-grid-2">
            <div class="form-group">
                <label for="person-name">Nome</label>
                <input type="text" id="person-name" name="seo[person.name]" value="<?= htmlspecialchars($val('person.name')) ?>">
            </div>
            <div class="form-group">
                <label for="person-job">Cargo</label>
                <input type="text" id="person-job" name="seo[person.job_title]" value="<?= htmlspecialchars($val('person.job_title')) ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="person-description">Descrição</label>
            <textarea id="person-description" name="seo[person.description]" rows="2"><?= htmlspecialchars($val('person.description')) ?></textarea>
        </div>
        <div class="form-group">
            <label for="person-same-as">Perfis (um link por linha)</label>
            <textarea id="person-same-as" name="seo[person.same_as]" rows="3" placeholder="https://github.com/..."><?= htmlspecialchars($val('person.same_as')) ?></textarea>
            <small>GitHub, LinkedIn, Behance etc.</small>
        </div>
    </fieldset>

    <fieldset class="form-section">
        <legend class="form-section-title">Páginas</legend>
        <p class="form-section-hint">Em branco, cada página usa o texto padrão definido no código.</p>
        <?php foreach (Seo::PAGES as $page => $label): ?>
        <div class="page-seo-row">
            <h4><?= htmlspecialchars($label) ?></h4>
            <div class="form-group">
                <label for="page-<?= $page ?>-title">Título</label>
                <input type="text" id="page-<?= $page ?>-title" name="seo[page.<?= $page ?>.title]" placeholder="<?= htmlspecialchars($label) ?>"
                    data-counter="<?= Seo::TITLE_MAX - mb_strlen($siteSuffix) ?>" value="<?= htmlspecialchars($val("page.$page.title")) ?>">
            </div>
            <div class="form-group">
                <label for="page-<?= $page ?>-description">Descrição</label>
                <textarea id="page-<?= $page ?>-description" name="seo[page.<?= $page ?>.description]" rows="2"
                    data-counter="<?= Seo::DESCRIPTION_MAX ?>"><?= htmlspecialchars($val("page.$page.description")) ?></textarea>
            </div>
        </div>
        <?php endforeach; ?>
    </fieldset>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Salvar configurações</button>
        <a href="<?= admin_url('seo/index.php') ?>" class="btn-secondary">Cancelar</a>
    </div>
</form>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
