<section class="page-hero">
    <div class="container">
        <span class="section-label reveal-hero">03 // INSIGHTS</span>
        <h1 class="reveal-hero delay-1">Pensamentos & <span class="highlight">Código</span>.</h1>
        <p class="hero-sub reveal-hero delay-2">
            Explorando as fronteiras entre design, tecnologia e produtividade.
        </p>

        <!-- Search Bar -->
        <form id="blogSearchForm" class="blog-search reveal-hero delay-3" action="<?= url('blog') ?>" method="GET" role="search" aria-label="Pesquisar artigos">
            <div class="blog-search-inner">
                <svg class="blog-search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input 
                    type="search" 
                    id="blogSearchInput"
                    name="q" 
                    class="blog-search-input" 
                    placeholder="Pesquisar artigos, categorias..." 
                    value="<?= View::escape($searchQuery ?? '') ?>"
                    autocomplete="off"
                >
                <?php if (!empty($searchQuery)): ?>
                <a href="<?= url('blog') ?>" class="blog-search-clear" title="Limpar pesquisa" aria-label="Limpar pesquisa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <button type="submit" class="blog-search-btn hover-trigger" aria-label="Pesquisar">
                <span>Pesquisar</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>

        <?php if (!empty($searchQuery)): ?>
        <div class="blog-search-status reveal-hero delay-3">
            <p>
                <span class="highlight"><?= count($posts) ?></span> resultado<?= count($posts) !== 1 ? 's' : '' ?> para "<strong><?= View::escape($searchQuery) ?></strong>"
                <a href="<?= url('blog') ?>" class="search-clear-link">Limpar filtro</a>
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="blog-grid-section">
    <div class="container">

        <?php if (empty($posts)): ?>
        <div class="blog-empty-state reveal">
            <div class="blog-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M8 11h6"/></svg>
            </div>
            <h3>Nenhum artigo encontrado</h3>
            <p>Tente ajustar os termos de pesquisa ou <a href="<?= url('blog') ?>">ver todos os artigos</a>.</p>
        </div>
        <?php endif; ?>

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

        <?php 
        $paginationQuery = !empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '';
        if (($totalPages ?? 1) > 1): ?>
        <div class="pagination reveal">
            <?php if ($currentPage > 1): ?>
                <a href="<?= url('blog?page=' . ($currentPage - 1) . $paginationQuery) ?>" class="btn-outline hover-trigger">Anterior</a>
            <?php else: ?>
                <span class="btn-outline disabled">Anterior</span>
            <?php endif; ?>
            
            <span class="page-info">Página <?= $currentPage ?> de <?= $totalPages ?></span>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= url('blog?page=' . ($currentPage + 1) . $paginationQuery) ?>" class="btn-outline hover-trigger">Próxima</a>
            <?php else: ?>
                <span class="btn-outline disabled">Próxima</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
