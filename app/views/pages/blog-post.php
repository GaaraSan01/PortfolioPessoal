<article class="blog-post-article">
    <!-- Hero do Post -->
    <section class="page-hero blog-post-hero">
        <div class="container">
            <a href="<?= url('blog') ?>" class="back-link reveal-hero">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Todos os Artigos
            </a>
            <div class="post-hero-meta reveal-hero">
                <span class="post-category"><?= htmlspecialchars($post['category']) ?></span>
                <span class="post-dot">·</span>
                <span class="post-date"><?= htmlspecialchars($post['date']) ?></span>
                <?php if (!empty($post['reading_time'])): ?>
                <span class="post-dot">·</span>
                <span class="post-read-time"><?= (int)$post['reading_time'] ?> min de leitura</span>
                <?php endif; ?>
            </div>
            <h1 class="reveal-hero delay-1"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="hero-sub reveal-hero delay-2"><?= htmlspecialchars($post['excerpt']) ?></p>
        </div>
    </section>

    <!-- Conteúdo do Post -->
    <section class="blog-post-content">
        <div class="container">
            <div class="post-body reveal">
                <?= $post['content_html'] ?>
            </div>

            <!-- Rodapé do Post -->
            <div class="post-footer reveal">
                <div class="post-tags-footer">
                    <span class="tag-label">Categoria</span>
                    <span class="tag-pill"><?= htmlspecialchars($post['category']) ?></span>
                </div>
                <div class="post-share">
                    <span>Compartilhar</span>
                    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode(url('blog/post/' . $post['slug'])) ?>" target="_blank" rel="noopener" aria-label="Compartilhar no Twitter/X">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.26 5.632 5.905-5.632Zm-1.161 17.52h1.833L7.084 4.126H5.117Z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(url('blog/post/' . $post['slug'])) ?>&title=<?= urlencode($post['title']) ?>" target="_blank" rel="noopener" aria-label="Compartilhar no LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                </div>
            </div>

            <!-- CTA de Volta -->
            <div class="post-back-cta reveal">
                <a href="<?= url('blog') ?>" class="project-nav-back hover-trigger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Todos os Artigos
                </a>
            </div>
        </div>
    </section>
</article>

<script>
// Envolver tabelas em .table-wrapper para scroll horizontal e estilização
document.querySelectorAll('.post-body table').forEach(function(table) {
    if (!table.parentElement.classList.contains('table-wrapper')) {
        var wrapper = document.createElement('div');
        wrapper.className = 'table-wrapper';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    }
});
</script>
