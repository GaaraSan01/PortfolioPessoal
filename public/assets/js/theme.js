// Carregado de forma síncrona no <head>, antes do CSS, para o tema não piscar: escolha salva > preferência do sistema
(function () {
    var t; try { t = localStorage.getItem('theme'); } catch (e) {}
    if (!t) t = matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
    document.documentElement.dataset.theme = t;
})();
    
