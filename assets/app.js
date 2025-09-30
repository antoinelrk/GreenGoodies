/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

document.addEventListener('DOMContentLoaded', () => {
    const popup   = document.querySelector('#confirmPopup');
    const dialog  = document.querySelector('#confirmDialog');
    const msgEl   = document.querySelector('#confirmMessage');
    const btnOk   = document.querySelector('#confirmOk');
    const btnNo   = document.querySelector('#confirmCancel');

    let currentForm = null;
    let lastFocused = null;

    const open = (message, form) => {
        currentForm = form || null;
        lastFocused = document.activeElement;
        if (message) msgEl.textContent = message;
        popup.classList.remove('hidden');
        btnOk.focus();
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        popup.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (lastFocused) lastFocused.focus();
        currentForm = null;
    };

    // TODO: Rename .js-confirm to .js-confirm-button or another more specific name
    document.querySelectorAll('.js-confirm').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = btn.dataset.form
                ? document.querySelector(btn.dataset.form)
                : btn.closest('form');

            if (!form) return;

            const message = btn.dataset.message || 'Es-tu sûr ?';
            open(message, form);
        });
    });

    btnNo.addEventListener('click', close);
    btnOk.addEventListener('click', () => {
        if (currentForm) currentForm.submit();
    });

    popup.addEventListener('click', (e) => {
        if (!dialog.contains(e.target)) close();
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
            close();
        }
    });
});
