/**
 * main.js
 * Application bootstrap. Initialises modules that depend on the DOM.
 * All modules are already loaded before this file via layout.
 */

document.addEventListener('DOMContentLoaded', () => {

    // Initialise cart module (POS page only)
    if (typeof cartModule !== 'undefined') {
        cartModule.init();
    }

    // Close dropdown on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (uiStore.getState().profileDropdownOpen) {
                uiStore.toggleProfile();
            }
        }
    });

    // Toast utility (used throughout)
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-5 py-3.5
            bg-white shadow-xl rounded-2xl border text-sm font-semibold
            ${type === 'success' ? 'border-green-200 text-green-600' : 'border-red-200 text-red-600'}
            transition-all translate-y-0 opacity-100`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity   = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };
});
