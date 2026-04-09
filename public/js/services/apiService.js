/**
 * apiService.js
 * Centralised HTTP service for all backend communication.
 */

const apiService = (() => {

    function _getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    }

    async function post(url, data) {
        const response = await fetch(url, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': _getCsrf(),
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            const err = await response.json().catch(() => ({ message: 'Server error' }));
            throw new Error(err.message ?? 'Request failed');
        }

        return response.json();
    }

    async function get(url) {
        const response = await fetch(url, {
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': _getCsrf(),
            },
        });

        if (!response.ok) {
            throw new Error('Request failed');
        }

        return response.json();
    }

    return { post, get };
})();

window.apiService = apiService;
