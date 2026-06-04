<footer id="contato" class="footer-section">
    <div class="container footer-grid">
        <div class="cta-col reveal">
            <span class="section-label">03 // CONTATO</span>
            <h2>TEM UM PROJETO?<br>VAMOS <span class="stroke-text">CONVERSAR</span>.</h2>
            <p>Preencha os campos ao lado para iniciarmos o desenvolvimento da sua ideia.</p>
            <div class="contact-info">
                <p>E-mail: <?= EMAIL_TO ?></p>
                <p>Loc: São José dos Pinhais, PR</p>
            </div>
        </div>
        
        <div class="form-col reveal delay-1">
            <form class="pro-form" id="contactForm">
                <!-- Campo Honeypot (proteção anti-spam) - INVISÍVEL -->
                <input type="text" name="website" style="display: none;" tabindex="-1" autocomplete="off">
                
                <div class="input-group">
                    <input type="text" id="nome" name="nome" required placeholder=" " autocomplete="name" class="hover-trigger" maxlength="50">
                    <label for="nome">Seu Nome</label>
                    <div class="line-bg"></div>
                </div>

                <div class="input-group">
                    <input type="tel" id="number" name="number" required placeholder=" " autocomplete="tel" class="hover-trigger">
                    <label for="number">Seu Numero</label>
                    <div class="line-bg"></div>
                </div>
                
                <div class="input-group">
                    <input type="email" id="email" name="email" required placeholder=" " autocomplete="email" class="hover-trigger" maxlength="255">
                    <label for="email">E-mail Corporativo</label>
                    <div class="line-bg"></div>
                </div>
                
                <div class="input-group">
                    <textarea id="msg" name="mensagem" required placeholder=" " rows="3" class="hover-trigger" maxlength="1000"></textarea>
                    <label for="msg">Sobre o Projeto</label>
                    <div class="line-bg"></div>
                </div>
                
                <button type="submit" class="submit-btn hover-trigger">
                    <span class="btn-text">ENVIAR PROPOSTA</span>
                    <span class="btn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                    </span>
                </button>
            </form>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="container bottom-flex">
            <p>&copy; <?= date('Y') ?> Vinicius H M Santos.</p>
            <div class="social-links">
                <a href="https://www.linkedin.com/in/viniciushms/" class="hover-trigger" target="_blank" aria-label="Meu perfil do Linkedin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-linkedin-icon lucide-linkedin"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <a href="https://github.com/GaaraSan01" class="hover-trigger" target="_blank" aria-label="Meu perfil do Github">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-github-icon lucide-github"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/><path d="M9 18c-4.51 2-5-2-7-2"/></svg>
                </a>
                <a href="https://www.instagram.com/vinih.io/" class="hover-trigger" target="_blank" aria-label="Meu perfil do Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                </a>
            </div>
        </div>
    </div>
</footer>
