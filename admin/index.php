<?php
require_once __DIR__ . '/auth.php';

// Já logado?
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($user === ADMIN_USER && $pass === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $user;
        $_SESSION['admin_csrf']      = bin2hex(random_bytes(32));
        header('Location: ' . BASE_URL . '/admin/dashboard.php');
        exit;
    } else {
        $error = 'Usuário ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/admin.css">
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="admin-login-body">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">VH<span class="accent-dot">.</span></div>
        <h1>Painel Admin</h1>
        <p class="login-sub">Acesso restrito ao proprietário.</p>

        <?php if ($error): ?>
        <div class="admin-alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn-primary full-width">Entrar</button>
        </form>
    </div>
</div>

</body>
</html>
