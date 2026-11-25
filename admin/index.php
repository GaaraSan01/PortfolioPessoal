<?php
require_once __DIR__ . '/auth.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $user]);
        $dbUser = $stmt->fetch();

        if (login_throttled($pdo, $ip)) {
            $error = 'Muitas tentativas. Aguarde 15 minutos.';
        } elseif ($dbUser && password_verify($pass, $dbUser['password_hash'])) {
            login_succeeded($pdo, $ip);
            session_regenerate_id(true);
            // Rehash the password if it needs to be updated (e.g. options changed)
            if (password_needs_rehash($dbUser['password_hash'], PASSWORD_ARGON2ID)) {
                $newHash = password_hash($pass, PASSWORD_ARGON2ID);
                $updateStmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
                $updateStmt->execute(['hash' => $newHash, 'id' => $dbUser['id']]);
            }

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $dbUser['username'];
            $_SESSION['admin_user_id']   = $dbUser['id'];
            $_SESSION['admin_csrf']      = bin2hex(random_bytes(32));
            $_SESSION['admin_last_seen'] = time();
            header('Location: ' . BASE_URL . '/admin/dashboard.php');
            exit;
        } else {
            login_failed($pdo, $ip);
            $error = 'Usuário ou senha incorretos.';
        }
    } catch (Exception $e) {
        $error = 'Erro de autenticação.';
        error_log("Database Error on Login: " . $e->getMessage());
    }
}
?>
<?php
$page_title = 'Admin — Login';
$body_class = 'admin-login-body';
$hide_layout = true;
include __DIR__ . '/partials/header.php';
?>

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

<?php include __DIR__ . '/partials/footer.php'; ?>
