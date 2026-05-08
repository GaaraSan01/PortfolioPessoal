<aside class="admin-sidebar">
    <div class="sidebar-logo">VH<span class="accent-dot">.</span> <span class="sidebar-logo-sub">Admin</span></div>
    <nav class="sidebar-nav">
        <a href="<?= admin_url('dashboard.php') ?>" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            Dashboard
        </a>
        <div class="nav-section-label">Conteúdo</div>
        <a href="<?= admin_url('projects/index.php') ?>" class="nav-item <?= strpos($_SERVER['PHP_SELF'], '/projects/') !== false ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Projetos
        </a>
        <a href="<?= admin_url('posts/index.php') ?>" class="nav-item <?= strpos($_SERVER['PHP_SELF'], '/posts/') !== false ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            Blog Posts
        </a>
        <div class="nav-section-label">Site</div>
        <a href="<?= BASE_URL . "/" ?>" target="_blank" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            Ver Portfólio ↗
        </a>
    </nav>
</aside>
