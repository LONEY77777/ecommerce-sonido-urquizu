import './bootstrap';

/**
 * Toggle the mobile navigation drawer.
 */
document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-nav-toggle]');

    if (!toggle) {
        return;
    }

    const menu = document.getElementById('mobile-menu');

    if (menu) {
        const isOpen = menu.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', String(!isOpen));
    }
});

/**
 * Open and close the native <dialog> confirmation modals.
 *
 * Triggers carry data-confirm-open="<dialog id>"; the dialog's own buttons
 * carry data-confirm-close.
 */
document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-confirm-open]');

    if (opener) {
        event.preventDefault();
        document.getElementById(opener.dataset.confirmOpen)?.showModal();

        return;
    }

    const closer = event.target.closest('[data-confirm-close]');

    if (closer) {
        event.preventDefault();
        closer.closest('dialog')?.close();
    }
});

/**
 * Submit a form as soon as one of its filter controls changes, so category and
 * status dropdowns behave like links without needing an Apply button.
 */
document.addEventListener('change', (event) => {
    const control = event.target.closest('[data-auto-submit]');

    control?.form?.requestSubmit();
});
