/**
 * uiStore.js
 * Manages all UI state: modals, dropdowns, panel position.
 */

const uiStore = (() => {
    let state = {
        checkoutOpen:         false,
        profileDropdownOpen:  false,
        orderPanelPosition:   { x: 0, y: 0 },
    };

    // ── Profile Dropdown ──────────────────────────────────────────────────────
    function toggleProfile() {
        state.profileDropdownOpen = !state.profileDropdownOpen;
        _renderProfileDropdown();
    }

    function _renderProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        const overlay  = document.getElementById('dropdown-overlay');

        if (!dropdown) return;

        if (state.profileDropdownOpen) {
            dropdown.classList.remove('hidden');
            overlay?.classList.remove('hidden');
            requestAnimationFrame(() => {
                dropdown.style.opacity   = '1';
                dropdown.style.transform = 'scale(1)';
            });
        } else {
            dropdown.style.opacity   = '0';
            dropdown.style.transform = 'scale(0.95)';
            overlay?.classList.add('hidden');
            setTimeout(() => dropdown.classList.add('hidden'), 150);
        }
    }

    // ── Checkout Modal ─────────────────────────────────────────────────────────
    function toggleCheckout() {
        state.checkoutOpen = !state.checkoutOpen;
    }

    // ── Panel Position ─────────────────────────────────────────────────────────
    function setPanelPosition(x, y) {
        state.orderPanelPosition = { x, y };
    }

    function savePanelPosition() {
        localStorage.setItem('orderPanelPosition', JSON.stringify(state.orderPanelPosition));
    }

    function loadPanelPosition() {
        const saved = localStorage.getItem('orderPanelPosition');
        if (saved) {
            try {
                state.orderPanelPosition = JSON.parse(saved);
            } catch (_) {
                // ignore
            }
        }
        return state.orderPanelPosition;
    }

    function getState() {
        return { ...state };
    }

    return { toggleProfile, toggleCheckout, setPanelPosition, savePanelPosition, loadPanelPosition, getState };
})();

window.uiStore = uiStore;
