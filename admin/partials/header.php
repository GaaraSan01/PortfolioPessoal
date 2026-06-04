<?php
$page_title = $page_title ?? 'Admin VH';
$body_class = $body_class ?? 'admin-body';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= uploads('favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= uploads('favicon/favicon-16x16.png') ?>">
    <link rel="shortcut icon" href="<?= uploads('favicon/favicon.ico') ?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= uploads('favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= uploads('favicon/site.webmanifest') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;800&family=Inter:wght@400;500&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/admin.css">
    <?php if (!empty($extra_css)): ?>
        <style><?= $extra_css ?></style>
    <?php endif; ?>
    <?php if (!empty($extra_head)): ?>
        <?= $extra_head ?>
    <?php endif; ?>
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php if (empty($hide_layout)): ?>
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-main">
        <?php include __DIR__ . '/topbar.php'; ?>
        <div class="admin-content">
<?php endif; ?>
