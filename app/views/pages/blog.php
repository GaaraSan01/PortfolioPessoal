<section class="page-hero">
    <div class="container">
        <span class="section-label reveal-hero">03 // INSIGHTS</span>
        <h1 class="reveal-hero delay-1">Pensamentos & <span class="highlight">Código</span>.</h1>
        <p class="hero-sub reveal-hero delay-2">
            Explorando as fronteiras entre design, tecnologia e produtividade.
        </p>
    </div>
</section>

<section class="blog-grid-section">
    <div class="container">
        <div class="blog-grid">
            <?php 
            $delay = 1;
            foreach ($posts as $post): 
            ?>
            <article class="blog-card reveal delay-<?= $delay ?>">
                <div class="blog-card-inner">
                    <div class="blog-meta">
                        <span class="category"><?= View::escape($post['category']) ?></span>
                        <span class="date"><?= View::escape($post['date']) ?></span>
                    </div>
                    <a href="<?= url('blog/post/' . $post['slug']) ?>" class="blog-link">
                        <h2><?= View::escape($post['title']) ?></h2>
                        <p><?= View::escape($post['excerpt']) ?></p>
                        <div class="read-more">
                            <span>Ler Artigo</span> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>
            </article>
            <?php 
            $delay++;
            endforeach; 
            ?>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
        <div class="pagination reveal">
            <?php if ($currentPage > 1): ?>
                <a href="<?= url('blog?page=' . ($currentPage - 1)) ?>" class="btn-outline hover-trigger">Anterior</a>
            <?php else: ?>
                <span class="btn-outline disabled">Anterior</span>
            <?php endif; ?>
            
            <span class="page-info">Página <?= $currentPage ?> de <?= $totalPages ?></span>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= url('blog?page=' . ($currentPage + 1)) ?>" class="btn-outline hover-trigger">Próxima</a>
            <?php else: ?>
                <span class="btn-outline disabled">Próxima</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
