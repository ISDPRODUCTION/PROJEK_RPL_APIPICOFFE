/**
 * cartStore.js
 * Observer-pattern cart state management.
 * UI must NEVER directly mutate state — only via store methods.
 */

const cartStore = (() => {
    // ── Private State ──────────────────────────────────────────────────────────
    let state = {
        items: [],
        subtotal: 0,
        tax: 0,
        total: 0,
    };

    // ── Subscribers ────────────────────────────────────────────────────────────
    const subscribers = [];

    function notify() {
        subscribers.forEach(cb => cb(getState()));
    }

    // ── Public API ─────────────────────────────────────────────────────────────
    function subscribe(callback) {
        subscribers.push(callback);
    }

    function getState() {
        return { ...state, items: state.items.map(i => ({ ...i })) };
    }

    function addItem(product) {
    const existing = state.items.find(i => i.product_id === product.product_id);

    if (existing) {
        if (existing.quantity >= product.stock) {
            alert(`Stok "${product.name}" tidak cukup! Tersedia: ${product.stock}`);
            return;
        }
        existing.quantity += 1;
    } else {
        state.items.push({
            product_id: product.product_id,
            name:       product.name,
            price:      product.price,
            image:      product.image,
            stock:      product.stock,
            quantity:   1,
        });
    }

    calculateTotals();
    notify();
}

    function removeItem(productId) {
        state.items = state.items.filter(i => i.product_id !== productId);
        calculateTotals();
        notify();
    }

    function increaseQty(productId) {
        const item = state.items.find(i => i.product_id === productId);
        if (item) {
            item.quantity += 1;
            calculateTotals();
            notify();
        }
    }

    function decreaseQty(productId) {
        const item = state.items.find(i => i.product_id === productId);
        if (item) {
            item.quantity -= 1;
            if (item.quantity <= 0) {
                removeItem(productId);
                return;
            }
            calculateTotals();
            notify();
        }
    }

    function calculateTotals() {
        state.subtotal = state.items.reduce((acc, i) => acc + i.price * i.quantity, 0);
        state.tax      = Math.round(state.subtotal * 0.10);
        state.total    = state.subtotal + state.tax;
    }

    function reset() {
        state = { items: [], subtotal: 0, tax: 0, total: 0 };
        notify();
    }

    return { subscribe, getState, addItem, removeItem, increaseQty, decreaseQty, calculateTotals, reset };
})();

// Make globally accessible
window.cartStore = cartStore;
