<section class="error-page" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 100px 5%;">
    <div class="container" style="text-align: center;">
        <div class="error-code" style="font-family: var(--font-heading); font-size: clamp(8rem, 20vw, 15rem); font-weight: 800; line-height: 1; opacity: 0.1; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: -1;">
            404
        </div>
        
        <h1 class="section-title" style="margin-bottom: 1.5rem;">Oops! Perdido no <span class="highlight">espaço?</span></h1>
        
        <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 500px; margin: 0 auto 3rem;">
            A página que você está procurando não existe ou foi movida para outra dimensão digital.
        </p>
        
        <div class="error-actions" style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= url('') ?>" class="btn-primary" style="padding: 1.2rem 2.5rem; background: var(--accent); color: #fff; font-weight: 700; border-radius: 4px; transition: 0.3s;">
                Voltar para o Início
            </a>
            <a href="<?= url('projects') ?>" class="btn-outline" style="padding: 1.2rem 2.5rem; border: 1px solid var(--border); border-radius: 4px; transition: 0.3s;">
                Ver Projetos
            </a>
        </div>
    </div>
</section>

<style>
.error-page .btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
}
.error-page .btn-primary:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(255, 68, 0, 0.2);
}
</style>
