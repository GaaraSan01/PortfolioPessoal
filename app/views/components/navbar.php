<nav class="navbar reveal-nav">
    <a href="<?= url('/') ?>" class="logo hover-trigger">VH<span class="dot">.</span></a>
    
    <!-- Desktop Menu -->
    <ul class="nav-links desktop-only">
        <li><a href="<?= url('projects') ?>" class="hover-trigger">Projetos</a></li>
        <li><a href="<?= url('about') ?>" class="hover-trigger">Sobre</a></li>
        <li><a href="<?= url('blog') ?>" class="hover-trigger">Blog</a></li>
        <li><a href="<?= url('contact') ?>" class="btn-outline hover-trigger">Contato</a></li>
    </ul>

    <!-- Hamburger Button (Mobile/Tablet) -->
    <button class="hamburger-btn hover-trigger" aria-label="Abrir Menu">
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
        <button class="close-menu-btn hover-trigger" aria-label="Fechar Menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>
    <ul class="offcanvas-links">
        <li><a href="<?= url('/') ?>">Home</a></li>
        <li><a href="<?= url('projects') ?>">Projetos</a></li>
        <li><a href="<?= url('about') ?>">Sobre</a></li>
        <li><a href="<?= url('blog') ?>">Blog</a></li>
        <li><a href="<?= url('contact') ?>" class="btn-outline offcanvas-btn">Contato</a></li>
    </ul>
    <div class="offcanvas-footer">
        <p>Vamos criar algo incrível.</p>
        <a href="mailto:contato@vnicidigital.com.br" class="offcanvas-email">contato@vnicidigital.com.br</a>
    </div>
</aside>
