<!-- Hero Section -->
<header class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <span class="label reveal-hero">DESENVOLVIMENTO WEB</span>
            <h1 class="reveal-hero delay-1">
                CÓDIGO <span class="highlight">LIMPO</span>.<br>
                DESIGN <span class="stroke-text">IMPACTANTE</span>.
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