<?php
/**
 * Bloco "SEO" dos formulários de post e projeto.
 * Espera: $seoKey ("post.12" | "project.3" | null em itens novos), $seoUrl (URL pública ou prefixo),
 *         $seoTitleSuffix (ex. " | Blog"), $seoDescFallback (nome do campo usado como descrição padrão).
 */
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$seoVal = fn(string $field, string $key) => $isPost ? ($_POST[$field] ?? '') : ($seoKey ? Seo::get("$seoKey.$key") : '');
$siteSuffix = ' | ' . Seo::get('site.name', APP_NAME);
?>
<fieldset class="form-section">
    <legend class="form-section-title">SEO</legend>
    <p class="form-section-hint">Opcional. Em branco, o Google recebe o título e o resumo do conteúdo.</p>

    <div class="form-group">
        <label for="seo_title">Título nos buscadores</label>
        <input type="text" id="seo_title" name="seo_title" maxlength="120"
            data-counter="<?= Seo::TITLE_MAX - mb_strlen($siteSuffix) ?>"
            value="<?= htmlspecialchars($seoVal('seo_title', 'title')) ?>">
    </div>

    <div class="form-group">
        <label for="seo_description">Meta description</label>
        <textarea id="seo_description" name="seo_description" rows="2" maxlength="320"
            data-counter="<?= Seo::DESCRIPTION_MAX ?>"><?= htmlspecialchars($seoVal('seo_description', 'description')) ?></textarea>
    </div>

    <label class="form-check">
        <input type="checkbox" name="seo_noindex" value="1" <?= $seoVal('seo_noindex', 'noindex') ? 'checked' : '' ?>>
        <span>Ocultar dos buscadores <small>(noindex: a página continua acessível pelo link)</small></span>
    </label>

    <div class="serp-preview" aria-label="Prévia no Google"
        data-title="#seo_title" data-title-fallback="[name=title]" data-title-suffix="<?= htmlspecialchars($seoTitleSuffix) ?>" data-site-suffix="<?= htmlspecialchars($siteSuffix) ?>"
        data-desc="#seo_description" data-desc-fallback="[name=<?= htmlspecialchars($seoDescFallback) ?>]">
        <span class="serp-label">Prévia no Google</span>
        <span class="serp-url"><?= htmlspecialchars($seoUrl) ?></span>
        <span class="serp-title"></span>
        <span class="serp-desc"></span>
    </div>
</fieldset>
