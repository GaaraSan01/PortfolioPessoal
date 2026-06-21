// ===================================================================
// PORTFOLIO VINICIUS HENRIQUE 
// ===================================================================

// --- 1. LÓGICA DE CARREGAMENTO ---
const preloader = document.getElementById('preloader');
const titleAnimation = document.getElementsByClassName('stroke-text-svg-animacao');
console.log(titleAnimation)

function finishLoading() {
    document.body.classList.add('loaded');
    document.querySelectorAll('.reveal-hero').forEach(el => el.classList.add('active'));
    titleAnimation[0].classList.add('animar')
}

window.addEventListener('load', finishLoading);
setTimeout(finishLoading, 3000); // Fallback

// --- 1.5. OFFCANVAS MENU ---
const hamburgerBtn = document.querySelector('.hamburger-btn');
const closeMenuBtn = document.querySelector('.close-menu-btn');
const offcanvasMenu = document.querySelector('.offcanvas-menu');
const menuOverlay = document.querySelector('.menu-overlay');

function toggleMenu() {
    if(!offcanvasMenu) return;
    offcanvasMenu.classList.toggle('active');
    menuOverlay.classList.toggle('active');
    document.body.style.overflow = offcanvasMenu.classList.contains('active') ? 'hidden' : '';
}

if (hamburgerBtn && closeMenuBtn && offcanvasMenu && menuOverlay) {
    hamburgerBtn.addEventListener('click', toggleMenu);
    closeMenuBtn.addEventListener('click', toggleMenu);
    menuOverlay.addEventListener('click', toggleMenu);
    
    // Fecha o menu ao clicar em um link
    const offcanvasLinks = document.querySelectorAll('.offcanvas-links a');
    offcanvasLinks.forEach(link => {
        link.addEventListener('click', toggleMenu);
    });
}

// --- 2. CURSOR & INTERAÇÕES ---
const cursorDot = document.querySelector('[data-cursor-dot]');
const cursorOutline = document.querySelector('[data-cursor-outline]');
const hoverTriggers = document.querySelectorAll('.hover-trigger');
const isDesktop = window.matchMedia("(min-width: 969px)").matches;

if (isDesktop && cursorDot && cursorOutline) {
    window.addEventListener('mousemove', (e) => {
        const posX = e.clientX;
        const posY = e.clientY;

        cursorDot.style.left = `${posX}px`;
        cursorDot.style.top = `${posY}px`;

        cursorOutline.animate({
            left: `${posX}px`,
            top: `${posY}px`
        }, { duration: 500, fill: "forwards" });
    });

    hoverTriggers.forEach(item => {
        item.addEventListener('mouseenter', () => document.body.classList.add('hover-active'));
        item.addEventListener('mouseleave', () => document.body.classList.remove('hover-active'));
    });
}

// --- 3. PROJECT IMAGE REVEAL ---
const projectItems = document.querySelectorAll('.project-item');
const previewImg = document.getElementById('preview-img');

if (isDesktop && previewImg) {
    projectItems.forEach(item => {
        item.addEventListener('mousemove', (e) => {
            const imgUrl = item.getAttribute('data-image');
            previewImg.style.backgroundImage = `url(${imgUrl})`;
            previewImg.style.left = `${e.clientX}px`;
            previewImg.style.top = `${e.clientY}px`;
        });

        item.addEventListener('mouseenter', () => {
            previewImg.style.opacity = '1';
            previewImg.style.transform = 'translate(-50%, -50%) scale(1) rotate(-3deg)';
        });

        item.addEventListener('mouseleave', () => {
            previewImg.style.opacity = '0';
            previewImg.style.transform = 'translate(-50%, -50%) scale(0.8) rotate(0deg)';
        });
    });
}

// --- 4. SCROLL ANIMATION ---
const revealElements = document.querySelectorAll('.reveal');

const scrollActive = () => {
    const windowHeight = window.innerHeight;
    const elementVisible = 100;

    revealElements.forEach((reveal) => {
        const elementTop = reveal.getBoundingClientRect().top;
        if (elementTop < windowHeight - elementVisible) {
            reveal.classList.add('active');
        }
    });
};

window.addEventListener('scroll', scrollActive);
scrollActive();

// --- 5. SMOOTH SCROLL ---
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});


const form = document.getElementById('contactForm');

if (form) {
    const nomeInput = document.getElementById('nome');
    const numberInput = document.getElementById('number');
    const emailInput = document.getElementById('email');
    const msgInput = document.getElementById('msg');
    const submitBtn = form.querySelector('.submit-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnIcon = submitBtn.querySelector('.btn-icon');

    const originalBtnText = btnText.innerText;
    const originalBtnColor = window.getComputedStyle(submitBtn).backgroundColor;

    function showError(input, message) {
        removeError(input);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerText = message;
        errorDiv.style.cssText = `
            color: #ff4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            animation: fadeIn 0.3s ease;
        `;

        input.parentElement.appendChild(errorDiv);
        input.parentElement.classList.add('error');
    }

    function removeError(input) {
        const errorMsg = input.parentElement.querySelector('.error-message');
        if (errorMsg) errorMsg.remove();
        input.parentElement.classList.remove('error');
    }

    function validateNome() {
        const valor = nomeInput.value.trim();

        if (valor === '') {
            showError(nomeInput, 'Por favor, preencha seu nome');
            return false;
        }
        if (valor.length < 2) {
            showError(nomeInput, 'Nome muito curto');
            return false;
        }
        if (/[0-9]/.test(valor)) {
            showError(nomeInput, 'Nome não pode conter números');
            return false;
        }

        removeError(nomeInput);
        return true;
    }

    function validateNumber() {

        const valor = numberInput.value.trim();
        const numberRegex = /^[\d\s\-\(\)\+]+$/;

        if (!numberRegex.test(valor) || valor.length < 10) {
            showError(numberInput, 'Número de celular inválido!');
            return false;
        }
        if (valor === '') {
            showError(numberInput, 'Por favor, preencha seu número');
            return false
        }

        removeError(numberInput);
        return true;
    }

    function validateEmail() {
        const valor = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (valor === '') {
            showError(emailInput, 'Por favor, preencha seu e-mail');
            return false;
        }
        if (!emailRegex.test(valor)) {
            showError(emailInput, 'E-mail inválido');
            return false;
        }

        removeError(emailInput);
        return true;
    }

    function validateMensagem() {
        const valor = msgInput.value.trim();

        if (valor === '') {
            showError(msgInput, 'Por favor, escreva sua mensagem');
            return false;
        }
        if (valor.length < 10) {
            showError(msgInput, `Mensagem muito curta (mínimo 10 caracteres, você tem ${valor.length})`);
            return false;
        }

        removeError(msgInput);
        return true;
    }

    // Mascara para o input de numero de telefone
    numberInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length > 11) {
            value = value.slice(0, 11);
        }

        if (value.length > 10) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d*)/, '($1');
        }

        e.target.value = value;
    });

    // Validação em tempo real
    nomeInput.addEventListener('blur', validateNome);
    numberInput.addEventListener('blur', validateNumber);
    emailInput.addEventListener('blur', validateEmail);
    msgInput.addEventListener('blur', validateMensagem);

    [nomeInput, numberInput, emailInput, msgInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.parentElement.classList.contains('error')) {
                removeError(input);
            }
        });
    });


    function setLoadingState() {
        submitBtn.disabled = true;
        submitBtn.style.background = '#666';
        submitBtn.style.cursor = 'not-allowed';
        btnText.innerText = 'ENVIANDO...';
        btnIcon.innerHTML = '⏳';
    }

    function setSuccessState() {
        submitBtn.style.background = '#2ecc71';
        submitBtn.style.cursor = 'pointer';
        btnText.innerText = 'ENVIADO COM SUCESSO!';
        btnIcon.innerHTML = '✓';
        setTimeout(resetButtonState, 4000);
    }

    function setErrorState(message) {
        submitBtn.disabled = false;
        submitBtn.style.background = '#e74c3c';
        submitBtn.style.cursor = 'pointer';
        btnText.innerText = message || 'ERRO AO ENVIAR';
        btnIcon.innerHTML = '✕';
        setTimeout(resetButtonState, 4000);
    }

    function resetButtonState() {
        submitBtn.disabled = false;
        submitBtn.style.background = originalBtnColor;
        submitBtn.style.cursor = 'pointer';
        btnText.innerText = originalBtnText;
        btnIcon.innerHTML = '→';
    }

    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.style.cssText = `
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: ${type === 'success' ? '#2ecc71' : '#e74c3c'};
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            z-index: 10000;
            animation: slideIn 0.5s ease;
            max-width: 400px;
        `;

        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="font-size: 1.5rem;">${type === 'success' ? '✓' : '✕'}</span>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.5s ease';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Valida todos os campos
        const nomeValido = validateNome();
        const emailValido = validateEmail();
        const mensagemValida = validateMensagem();

        if (!nomeValido || !emailValido || !mensagemValida) {
            showToast('Por favor, corrija os erros no formulário', 'error');
            return;
        }

        // Prepara os dados
        const formData = new FormData(form);

        // Estado de loading
        setLoadingState();

        try {
            // Envia para o controller MVC
            const response = await fetch('contact/send', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                setSuccessState();
                showToast(data.message, 'success');
                form.reset();
                [nomeInput, emailInput, msgInput].forEach(removeError);
            } else {
                setErrorState('TENTE NOVAMENTE');
                showToast(data.message || 'Erro ao enviar mensagem', 'error');
            }

        } catch (error) {
            console.error('Erro:', error);
            setErrorState('ERRO DE CONEXÃO');
            showToast('Erro ao conectar com o servidor. Verifique sua conexão e tente novamente.', 'error');
        }
    });
}


const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(100px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes slideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100px); }
    }
    
    .input-group.error .line-bg {
        background: #e74c3c !important;
        height: 2px !important;
    }
    
    .input-group.error input,
    .input-group.error textarea {
        color: #ff8888;
    }
`;
document.head.appendChild(style);

console.log('%c ❤️ Portfolio - ViniciusH.', 'color: #fff; background: #000; padding: 10px; font-size: 14px;');
