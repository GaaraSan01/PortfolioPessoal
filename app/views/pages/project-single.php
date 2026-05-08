<section class="page-hero project-single-hero">
    <div class="container">
        <a href="<?= url('projects') ?>" class="back-link reveal-hero">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Todos os Projetos
        </a>
        <span class="section-label reveal-hero"><?= View::escape($project['category']) ?> · <?= View::escape($project['year']) ?></span>
        <h1 class="reveal-hero delay-1"><?= View::escape($project['title']) ?></h1>
        <p class="hero-sub reveal-hero delay-2"><?= View::escape($project['description']) ?></p>
    </div>
</section>

<!-- Imagem Principal -->
<div class="project-single-cover reveal">
    <img src="<?= $project['image_url'] ?>" alt="<?= View::escape($project['title']) ?>">
</div>

<!-- Conteúdo -->
<section class="project-single-content">
    <div class="container">
        <div class="project-single-grid">
            <!-- Coluna principal -->
            <div class="project-description reveal">
                <h2>Sobre o Projeto</h2>
                <p><?= nl2br(View::escape($project['long_description'] ?? $project['description'])) ?></p>
            </div>

            <!-- Sidebar -->
            <aside class="project-sidebar reveal delay-1">
                <!-- Tecnologias -->
                <?php if (!empty($project['technologies'])): ?>
                <div class="sidebar-block">
                    <h4>Tecnologias</h4>
                    <ul class="tech-tags">
                        <?php foreach ($project['technologies'] as $tech): ?>
                        <li><?= View::escape($tech) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Ano -->
                <div class="sidebar-block">
                    <h4>Ano</h4>
                    <p><?= htmlspecialchars($project['year']) ?></p>
                </div>

                <!-- Categoria -->
                <div class="sidebar-block">
                    <h4>Categoria</h4>
                    <p><?= htmlspecialchars($project['category']) ?></p>
                </div>

                <!-- Link Externo -->
                <?php if (!empty($project['url'])): ?>
                <a href="<?= htmlspecialchars($project['url']) ?>" target="_blank" rel="noopener" class="project-cta-btn hover-trigger">
                    Ver Projeto Ao Vivo
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                </a>
                <?php endif; ?>
            </aside>
        </div>

        <!-- Navegação entre projetos -->
        <div class="project-nav reveal">
            <a href="<?= url('projects') ?>" class="project-nav-back hover-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Ver todos os projetos
            </a>
        </div>
    </div>
</section>
