# Plano de implementação: melhorias de design

Origem: crítica de design da home (desktop 1440px, mobile 375px).
Branch sugerida: `feat/design-improvements` (a partir de `main`).
Escopo: só CSS/JS/views públicas. Nada de admin, banco ou rotas.

Ordem: cada fase é um commit independente e verificável no navegador.

---

## Fase 0 — Limpar o CSS duplicado (pré-requisito)

**Problema:** `public/assets/css/style.css` tem o bloco `/* --- RESET & BASIC --- */` duas vezes
(linhas 13–627 e 628–~1240). A segunda cópia é uma versão antiga e, por vir depois, **é ela que vale
hoje** na cascata. Editar a primeira cópia não teria efeito.

**Passos:**
1. `diff` entre os dois blocos para listar as diferenças reais (ex.: a 1ª cópia tem
   `.stroke-text-svg-animacao` + `@keyframes desenhar`; a 2ª tem `.desktop-only { display:none }` no
   media de 968px).
2. Levar para a 1ª cópia tudo o que só existe na 2ª.
3. Apagar a 2ª cópia.
4. Conferir visualmente home, about, blog, contato e 404 em 1440px e 375px antes/depois (screenshots).

**Pronto quando:** cada seletor aparece uma vez só e não há diferença visual.

---

## Fase 1 — Velocidade percebida (crítico)

### 1.1 Preloader
- `public/assets/js/main.js:13-14` — trocar `window.addEventListener('load', …)` por
  `DOMContentLoaded` e o fallback de 3000 ms por ~800 ms.
- `style.css` `.preloader` / `body.loaded .preloader` — reduzir a transição (0.8s → 0.4s).
- Alternativa mais simples: remover o preloader (`app/views/layouts/main.php` + CSS + JS).
  **Decisão do autor.** Recomendado: remover.

### 1.2 Cursor customizado
- `style.css` regra `* { cursor: none; }` (linha 18) — **remover**. Hoje esconde o cursor até em
  touch e se o JS falhar.
- Manter `.cursor-dot` / `.cursor-outline` como enfeite, só em `@media (hover: hover) and (pointer: fine)`.
- `main.js:269-294` — os `submitBtn.style.cursor = …` podem ficar (passam a fazer efeito).

**Pronto quando:** em 375px o conteúdo aparece sem tela preta; o cursor nativo aparece sempre.

---

## Fase 2 — Legibilidade e acessibilidade

| Item | Onde | Mudança |
|---|---|---|
| Altura de linha | `body` (style.css:25) | `line-height: 1.6;` (títulos já definem a sua) |
| Fonte dos campos | reset | `input, textarea, button, select { font: inherit; color: inherit; }` |
| Contraste do botão laranja | novo token em `:root` | `--accent-strong: #D93A00;` usar como fundo de botões com texto branco (≥4.5:1). `--accent` fica para texto/detalhes |
| `.btn-primary` da 404 | `app/views/pages/404.php:14` | trocar `background: var(--accent)` por `var(--accent-strong)`; idealmente mover estilos inline para o CSS |
| Rótulos de seção | `.section-label` | 12.8px → `0.875rem` (14px), `letter-spacing` mantido |
| Skill cards | `.skill-card p` | 14.4px → `1rem` |
| Alvos de toque | `.social-links a`, `.social-list a`, `.hamburger-btn` | `min-width/min-height: 44px; display:inline-flex; align-items:center; justify-content:center;` |
| Movimento reduzido | fim do style.css | ver bloco abaixo |
| Alt do hero | `app/views/pages/home.php` (`img-hero.avif`) | `alt=""` se decorativa, ou descrição real |

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; scroll-behavior: auto !important; }
  .reveal, .reveal-hero { opacity: 1; transform: none; }
  .preloader { display: none; }
}
```

**Pronto quando:** Lighthouse Accessibility ≥ 95 na home; botão laranja ≥ 4.5:1.

---

## Fase 3 — Hierarquia e conversão

### 3.1 CTA no hero
- `app/views/pages/home.php`, logo após `<p class="hero-sub …">`: dois links
  - "Ver projetos" → `#projetos` (botão sólido, `--accent-strong`)
  - "Fale comigo" → `url('contact')` (reusar `.btn-outline`)
- Reusar classes existentes; criar só `.hero-actions { display:flex; gap:1rem; margin-top:2rem; flex-wrap:wrap }`.

### 3.2 Projetos visíveis sem hover
- Hoje a imagem só aparece no hover (`.project-preview-img` via `data-image`), ou seja, nunca no mobile.
- Em `max-width: 968px`: renderizar `<img loading="lazy">` dentro de cada `.project-item`
  (o `data-image` já tem a URL) e esconder o preview flutuante.

### 3.3 E-mail vazio no contato
- `app/views/components/footer.php:8` — envolver em `<?php if (EMAIL_TO): ?>` e escapar com
  `View::escape(EMAIL_TO)` (hoje sai sem escape).

**Pronto quando:** no 375px dá para ver pelo menos uma imagem de projeto sem interagir; o hero tem 2 CTAs.

---

## Fase 4 — Tokens (opcional, só se sobrar tempo)
- Adicionar em `:root`: `--space-1..6` (0.5/1/1.5/2/4/8rem) e `--text-sm/base/lg`.
- Unificar breakpoints em 968px e 1252px (remover o par 1252/1253 trocando por `min-width: 1253px` ↔ `max-width: 1252.98px`, ou um único `968px`).
- Não reescrever o CSS inteiro: aplicar só nas regras tocadas nas fases 1–3.

---

## Fora de escopo
- Reescrever o texto do about (é conteúdo, fica com o autor).
- Mudar a paleta ou a tipografia.
- Admin (`admin/`).

## Riscos
- **Fase 0** é a mais arriscada: mudar a ordem da cascata altera a aparência. Comparar screenshots.
- Remover o preloader pode deixar à mostra um flash de fonte (FOUT). Aceitável; `display=swap` já está ativo.
- Banco local está vazio e `public/uploads/` não está no git: para testar a 3.2 é preciso cadastrar
  um projeto no admin ou copiar as imagens de produção.

## Verificação (fim de cada fase)
1. `php -S localhost:8099 router.php` (o roteador precisa reproduzir o `.htaccess`: arquivo existe → serve; senão `index.php?url=…`, sem `url` na raiz).
2. Home, /about, /blog, /contact, /404 em 1440px e 375px.
3. Navegar só com teclado (Tab) pela home e pelo menu mobile.
