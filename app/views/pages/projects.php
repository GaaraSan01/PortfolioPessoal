<section class="page-hero">
    <div class="container">
        <span class="section-label reveal-hero">02 // TRABALHOS</span>
        <h1 class="reveal-hero delay-1">Projetos <span class="stroke-text">Selecionados</span>.</h1>
        <p class="hero-sub reveal-hero delay-2">
            Uma amostra do meu trabalho recente, variando de interfaces experimentais a sistemas complexos de agendamento.
        </p>
    </div>
</section>

<section class="projects-grid-section">
    <div class="container">
        <div class="projects-grid">
            <?php 
            $delay = 1;
            foreach ($projects as $project): 
            ?>
            <a href="<?= url('projects/show/' . $project['slug']) ?>" class="project-card reveal delay-<?= $delay ?>">
                <div class="card-img-wrapper">
                    <img src="<?= $project['image_url'] ?>" alt="<?= View::escape($project['title']) ?>">
                    <div class="card-overlay">
                        <span class="year"><?= View::escape($project['year']) ?></span>
                    </div>
                </div>
                <div class="card-info">
                    <span class="category"><?= View::escape($project['category']) ?></span>
                    <h3><?= View::escape($project['title']) ?></h3>
                    <p><?= View::escape($project['description']) ?></p>
                    <div class="card-footer">
                        <span class="view-project">Ver Projeto</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                    </div>
                </div>
            </a>
            <?php 
            $delay++;
            endforeach; 
            ?>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
        <div class="pagination reveal">
            <?php if ($currentPage > 1): ?>
                <a href="<?= url('projects?page=' . ($currentPage - 1)) ?>" class="btn-outline hover-trigger">Anterior</a>
            <?php else: ?>
                <span class="btn-outline disabled">Anterior</span>
            <?php endif; ?>
            
            <span class="page-info">Página <?= $currentPage ?> de <?= $totalPages ?></span>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= url('projects?page=' . ($currentPage + 1)) ?>" class="btn-outline hover-trigger">Próxima</a>
            <?php else: ?>
                <span class="btn-outline disabled">Próxima</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
