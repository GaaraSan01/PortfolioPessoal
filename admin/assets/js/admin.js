
document.addEventListener('DOMContentLoaded', () => {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const writePanes = document.querySelectorAll('.write-pane');
    const previewPanes = document.querySelectorAll('.preview-pane');
    const mdInput = document.getElementById('markdownInput');
    const previewEl = document.querySelector('.preview-content');

    function renderPreview() {
        if (!mdInput || !previewEl || typeof marked === 'undefined' || typeof DOMPurify === 'undefined') return;
        previewEl.innerHTML = DOMPurify.sanitize(marked.parse(mdInput.value || ''));
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const tab = btn.dataset.tab;

            if (tab === 'write') {
                writePanes.forEach(p => p.classList.add('active'));
                previewPanes.forEach(p => p.classList.remove('active'));
            } else {
                writePanes.forEach(p => p.classList.remove('active'));
                previewPanes.forEach(p => p.classList.add('active'));
                renderPreview();
            }
        });
    });

    // Atualizar preview ao digitar (debounce 300ms)
    if (mdInput) {
        let debounceTimer;
        mdInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                // Só re-renderiza se preview estiver visível
                const previewVisible = document.querySelector('.preview-pane.active');
                if (previewVisible) renderPreview();
            }, 300);
        });
    }

    
    const alerts = document.querySelectorAll('.admin-alert.success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    
    // Contador de caracteres: <input data-counter="160">
    document.querySelectorAll('[data-counter]').forEach(field => {
        const max = Number(field.dataset.counter);
        const counter = document.createElement('small');
        counter.className = 'char-counter';
        counter.setAttribute('aria-live', 'polite');
        field.insertAdjacentElement('afterend', counter);
        const update = () => {
            const len = field.value.length;
            counter.textContent = `${len} / ${max} caracteres` + (len > max ? ' (será cortado no Google)' : '');
            counter.classList.toggle('is-over', len > max);
        };
        field.addEventListener('input', update);
        update();
    });

    // Prévia no Google: usa o campo de SEO ou, vazio, o campo de conteúdo equivalente
    document.querySelectorAll('.serp-preview').forEach(box => {
        const pick = (sel) => sel ? document.querySelector(sel) : null;
        const title = pick(box.dataset.title), titleFb = pick(box.dataset.titleFallback);
        const desc = pick(box.dataset.desc), descFb = pick(box.dataset.descFallback);
        const cut = (s, n) => s.length > n ? s.slice(0, n - 1).trimEnd() + '…' : s;
        const update = () => {
            // Igual ao Seo::meta: título próprio substitui "Conteúdo | Seção"; o nome do site vai sempre no fim
            const t = (title?.value.trim() || (titleFb?.value.trim() || 'Sem título') + (box.dataset.titleSuffix || '')) + (box.dataset.siteSuffix || '');
            const d = desc?.value.trim() || descFb?.value.trim() || 'Sem descrição: o Google vai escolher um trecho da página.';
            box.querySelector('.serp-title').textContent = cut(t, 60);
            box.querySelector('.serp-desc').textContent = cut(d, 160);
        };
        [title, titleFb, desc, descFb].forEach(el => el?.addEventListener('input', update));
        update();
    });

    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm(form.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});

// Confirmação de exclusão (substitui onsubmit inline, bloqueado pela CSP)
document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', e => {
        if (!confirm(form.dataset.confirm)) e.preventDefault();
    });
});
