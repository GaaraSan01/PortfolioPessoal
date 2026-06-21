<!-- Hero Section -->
<header class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <span class="label reveal-hero">DESENVOLVIMENTO WEB</span>
            <h1 class="reveal-hero delay-1">
                CÓDIGO <span class="highlight">LIMPO</span>.<br>
                DESIGN <span class="stroke-text">
                    <svg class="stroke-text-svg-animacao" aria-label="IMPACTANTE" width="603" height="76" viewBox="0 0 603 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M-5.47245e-05 73.899V1.44898H13.6619V73.899H-5.47245e-05ZM27.29 73.899V1.44898H52.6475L65.171 64.584H67.034L79.5575 1.44898H104.915V73.899H91.667V11.4885H89.804L77.384 73.899H54.821L42.401 11.4885H40.538V73.899H27.29ZM118.56 73.899V1.44898H148.368C152.922 1.44898 156.924 2.38048 160.374 4.24348C163.893 6.03748 166.619 8.59048 168.551 11.9025C170.552 15.2145 171.552 19.1475 171.552 23.7015V25.1505C171.552 29.6355 170.517 33.5685 168.447 36.9495C166.446 40.2615 163.686 42.849 160.167 44.712C156.717 46.506 152.784 47.403 148.368 47.403H132.222V73.899H118.56ZM132.222 34.983H147.023C150.266 34.983 152.888 34.086 154.889 32.292C156.89 30.498 157.89 28.0485 157.89 24.9435V23.9085C157.89 20.8035 156.89 18.354 154.889 16.56C152.888 14.766 150.266 13.869 147.023 13.869H132.222V34.983ZM170.497 73.899L189.541 1.44898H213.346L232.39 73.899H218.314L214.381 57.96H188.506L184.573 73.899H170.497ZM191.714 45.333H211.172L202.375 10.0395H200.512L191.714 45.333ZM267.987 75.348C259.017 75.348 251.91 72.864 246.666 67.896C241.422 62.859 238.8 55.683 238.8 46.368V28.98C238.8 19.665 241.422 12.5235 246.666 7.55548C251.91 2.51848 259.017 -1.79708e-05 267.987 -1.79708e-05C276.888 -1.79708e-05 283.753 2.44948 288.583 7.34849C293.482 12.1785 295.932 18.837 295.932 27.324V27.945H282.477V26.91C282.477 22.632 281.269 19.113 278.854 16.353C276.508 13.593 272.886 12.213 267.987 12.213C263.157 12.213 259.362 13.6965 256.602 16.6635C253.842 19.6305 252.462 23.667 252.462 28.773V46.575C252.462 51.612 253.842 55.6485 256.602 58.6845C259.362 61.6515 263.157 63.135 267.987 63.135C272.886 63.135 276.508 61.755 278.854 58.995C281.269 56.166 282.477 52.647 282.477 48.438V46.575H295.932V48.024C295.932 56.511 293.482 63.204 288.583 68.103C283.753 72.933 276.888 75.348 267.987 75.348ZM322.415 73.899V13.869H301.301V1.44898H357.191V13.869H336.077V73.899H322.415ZM354.856 73.899L373.9 1.44898H397.705L416.749 73.899H402.673L398.74 57.96H372.865L368.932 73.899H354.856ZM376.074 45.333H395.532L386.734 10.0395H384.871L376.074 45.333ZM425.421 73.899V1.44898H451.4L465.786 64.584H467.649V1.44898H481.104V73.899H455.126L440.739 10.764H438.876V73.899H425.421ZM511.525 73.899V13.869H490.411V1.44898H546.301V13.869H525.187V73.899H511.525ZM555.605 73.899V1.44898H602.18V13.869H569.267V31.1535H599.282V43.5735H569.267V61.479H602.801V73.899H555.605Z" />
                    </svg><span class="hidden-desktop">IMPACTANTE</span></span>.
            </h1>
            <p class="hero-sub reveal-hero delay-2">
                Construindo interfaces digitais de alta performance com foco em experiência do usuário e arquitetura escalável.
            </p>
        </div>
        <div class="hero-image-container reveal-hero delay-3">
            <img src="<?= uploads('/img-hero.avif') ?>" alt="Setup" class="hero-img">
        </div>
    </div>
    <a href="#projetos" class="scroll-indicator hover-trigger reveal-hero delay-3">
        <span class="scroll-text">SCROLL</span>
        <div class="line"></div>
    </a>
</header>

<!-- MARQUEE -->
<div class="marquee">
    <div class="track">
            <div class="content">
                &nbsp;FULL STACK / PYTHON / VUEJS / UI DESIGN / WORDPRESS / PERFORMANCE /&nbsp;FULL STACK / PHP / REACT / UI DESIGN / SEO / PERFORMANCE /
                &nbsp;FULL STACK / PYTHON / VUEJS / UI DESIGN / WORDPRESS / PERFORMANCE /&nbsp;FULL STACK / PHP / REACT / UI DESIGN / SEO / PERFORMANCE /
                &nbsp;FULL STACK / PYTHON / VUEJS / UI DESIGN / WORDPRESS / PERFORMANCE /&nbsp;FULL STACK / PHP / REACT / UI DESIGN / SEO / PERFORMANCE /
            </div>
    </div>
</div>

<!-- Projects Section -->
<section id="projetos" class="projects-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">01 // TRABALHOS</span>
            <h2 class="section-title">Projetos Selecionados<span class="dot">.</span></h2>
        </div>
        
        <div class="project-preview-wrapper">
            <div class="project-preview-img" id="preview-img"></div>
        </div>

        <div class="project-list">
            <?php foreach ($projects as $project): ?>
            <a href="<?= View::escape($project['url']) ?>" class="project-item hover-trigger reveal" data-image="<?= View::escape($project['image']) ?>">
                <div class="project-details">
                    <span class="project-num"><?= View::escape($project['num']) ?></span>
                    <h3><?= View::escape($project['title']) ?></h3>
                </div>
                <div class="project-category"><?= View::escape($project['category']) ?></div>
                <span class="arrow-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-up-right-icon lucide-move-up-right"><path d="M13 5H19V11"/><path d="M19 5L5 19"/></svg>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="sobre" class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-text reveal">
                <span class="section-label">02 // EXPERTISE</span>
                <h2>Estética encontra <br><span class="highlight">Funcionalidade.</span></h2>
                <p>Não sou apenas um programador, sou um parceiro estratégico. Com foco na intersecção entre design visual e arquitetura de software, crio soluções que não apenas parecem boas, mas escalam com seu negócio.</p>
                    
                <!-- <a href="#" class="btn-outline hover-trigger">
                    Baixar Catálogo
                </a> -->
            </div>
            <div class="skills-wrapper reveal">
                <div class="skill-card">
                    <h3>Frontend</h3>
                    <p>React, Next.js, Tailwind, VueJs, NuxtJs</p>
                </div>
                <div class="skill-card">
                    <h3>Backend</h3>
                    <p>Python, PHP, Java, PostgreSQL, Docker</p>
                </div>
                <div class="skill-card">
                    <h3>Design</h3>
                    <p>Figma, UI Prototyping, UX Design </p>
                </div>
            </div>
        </div>
    </div>
</section>