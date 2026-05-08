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

    <meta name="description" content="<?= $metaDescription ?? 'Portfolio Vinicius Henrique - Desenvolvedor Full Stack especializado em UI/UX e performance.' ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">
    <meta property="og:title" content="<?= ($title ?? 'Home') . ' | ' . APP_NAME ?>">
    <meta property="og:description" content="<?= $metaDescription ?? 'Portfolio Vinicius Henrique - Desenvolvedor Full Stack' ?>">
    <meta property="og:image" content="<?= uploads('og-image.jpg') ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">
    <meta property="twitter:title" content="<?= ($title ?? 'Home') . ' | ' . APP_NAME ?>">
    <meta property="twitter:description" content="<?= $metaDescription ?? 'Portfolio Vinicius Henrique - Desenvolvedor Full Stack' ?>">
    <meta property="twitter:image" content="<?= uploads('og-image.jpg') ?>">

    <title><?= ($title ?? 'Home') . ' | ' . APP_NAME ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Space+Grotesk:wght@400;600;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">

    <!-- Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Person",
      "name": "Vinicius Henrique",
      "url": "https://viniciush.vnicidigital.com.br/",
      "jobTitle": "Full Stack Developer",
      "description": "Desenvolvedor Full Stack especializado em experiências digitais modernas e performáticas.",
      "sameAs": [
        "https://github.com/vinicius",
        "https://linkedin.com/in/vinicius"
      ]
    }
    </script>
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
