<nav class="navbar reveal-nav">
    <a href="<?= url('/') ?>" class="logo hover-trigger">VH<span class="dot">.</span></a>
    
    <!-- Desktop Menu -->
    <ul class="nav-links desktop-only">
        <li><a href="<?= url('projects') ?>" class="hover-trigger">Projetos</a></li>
        <li><a href="<?= url('about') ?>" class="hover-trigger">Sobre</a></li>
        <li><a href="<?= url('blog') ?>" class="hover-trigger">Blog</a></li>
        <li><a href="<?= url('contact') ?>" class="btn-outline hover-trigger">Contato</a></li>
        <li><button type="button" class="theme-toggle hover-trigger" aria-label="Alternar tema claro/escuro">
            <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
            <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
        </button></li>
    </ul>

    <!-- Hamburger Button (Mobile/Tablet) -->
    <button class="hamburger-btn hover-trigger" aria-label="Abrir menu">
        <span class="hamburger-line line-1"></span>
        <span class="hamburger-line line-2"></span>
        <span class="hamburger-line line-3"></span>
    </button>
</nav>

<!-- Offcanvas Menu Overlay -->
<div class="menu-overlay"></div>

<!-- Offcanvas Menu Content -->
<aside class="offcanvas-menu">
    <div class="offcanvas-header">
        <span class="logo">VH<span class="dot">.</span></span>
        <button class="close-menu-btn hover-trigger" aria-label="Fechar menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>
    <ul class="offcanvas-links">
        <li><a href="<?= url('/') ?>">Home</a></li>
        <li><a href="<?= url('projects') ?>">Projetos</a></li>
        <li><a href="<?= url('about') ?>">Sobre</a></li>
        <li><a href="<?= url('blog') ?>">Blog</a></li>
        <li><a href="<?= url('contact') ?>" class="btn-outline offcanvas-btn">Contato</a></li>
        <li><button type="button" class="theme-toggle hover-trigger" aria-label="Alternar tema claro/escuro">
                <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
            </button></li>
    </ul>
   <!-- <div class="offcanvas-footer">
        <p>Vamos criar algo incrível.</p>
        <a href="mailto:<?= View::escape(EMAIL_TO) ?>" class="offcanvas-email"><?= View::escape(EMAIL_TO) ?></a>
    </div>-->
</aside>
