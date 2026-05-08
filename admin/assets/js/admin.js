
document.addEventListener('DOMContentLoaded', () => {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const writePanes = document.querySelectorAll('.write-pane');
    const previewPanes = document.querySelectorAll('.preview-pane');
    const mdInput = document.getElementById('markdownInput');
    const previewEl = document.querySelector('.preview-content');

    function renderPreview() {
        if (!mdInput || !previewEl || typeof marked === 'undefined') return;
        let html = marked.parse(mdInput.value || '');
        // Sanitização básica para o preview (remover <script>)
        html = html.replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gim, "<strong>[Script Removido por Segurança]</strong>");
        previewEl.innerHTML = html;
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

    
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm(form.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
