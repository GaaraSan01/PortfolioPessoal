<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CONFIGURAÇÃO DE FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= uploads('favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= uploads('favicon/favicon-16x16.png') ?>">
    <link rel="shortcut icon" href="<?= uploads('favicon/favicon.ico') ?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= uploads('favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= uploads('favicon/site.webmanifest') ?>">

<?php
    // Metas configuráveis em /admin/seo ($data vem de View::render)
    $seo = Seo::meta($data);
    $e = fn($s) => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    ?>
    <meta name="description" content="<?= $e($seo['description']) ?>">
    <meta name="robots" content="<?= $seo['robots'] ?>">
    <link rel="canonical" href="<?= $e($seo['canonical']) ?>">
    <?php if ($code = Seo::get('site.google_verification')): ?>
    <meta name="google-site-verification" content="<?= $e($code) ?>">
    <?php endif; ?>
    <?php if ($code = Seo::get('site.bing_verification')): ?>
    <meta name="msvalidate.01" content="<?= $e($code) ?>">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= $e($seo['type']) ?>">
    <meta property="og:site_name" content="<?= $e(Seo::get('site.name', APP_NAME)) ?>">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="<?= $e($seo['canonical']) ?>">
    <meta property="og:title" content="<?= $e($seo['title']) ?>">
    <meta property="og:description" content="<?= $e($seo['description']) ?>">
    <?php if ($seo['image']): ?>
    <meta property="og:image" content="<?= $e($seo['image']) ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $e($seo['title']) ?>">
    <meta name="twitter:description" content="<?= $e($seo['description']) ?>">
    <?php if ($seo['image']): ?>
    <meta name="twitter:image" content="<?= $e($seo['image']) ?>">
    <?php endif; ?>

    <title><?= $e($seo['title']) ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Space+Grotesk:wght@400;600;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">

    <!-- Structured Data (Schema.org) -->
    <script type="application/ld+json"><?= Seo::personJsonLd() ?></script>
</head>
<body>
    
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="loader-content">
            <div class="loader-text">VH<span class="dot">.</span></div>
            <div class="loading-bar"><div class="bar-fill"></div></div>
        </div>
    </div>

    <!-- Cursor customizado -->
    <div class="cursor-dot" data-cursor-dot></div>
    <div class="cursor-outline" data-cursor-outline></div>

    <!-- Noise overlay -->
    <div class="noise-overlay"></div>

    <!-- Navbar -->
    <?php View::component('navbar'); ?>

    <!-- Conteúdo da página -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php View::component('footer'); ?>

    <!-- JavaScript -->
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
