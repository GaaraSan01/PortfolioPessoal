<header class="admin-topbar">
    <div class="topbar-left">
        <h1 class="page-title"><?= $pageTitle ?? 'Admin' ?></h1>
    </div>
    <div class="topbar-right">
        <span class="topbar-user"><?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?></span>
        <form action="<?= admin_url('logout.php') ?>" method="POST" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <button type="submit" class="btn-logout" style="background:transparent; cursor:pointer;">Sair</button>
        </form>
    </div>
</header>
