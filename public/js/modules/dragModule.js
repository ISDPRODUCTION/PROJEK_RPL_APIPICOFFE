/**
 * dragModule.js
 * Enables drag-and-drop for floating panels with viewport clamping.
 * Position is persisted to localStorage via uiStore.
 */

const dragModule = (() => {

    function enableDrag(element, handle) {
        let isDragging = false;
        let startX, startY, initLeft, initTop;

        handle.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX  = e.clientX;
            startY  = e.clientY;
            initLeft = element.offsetLeft;
            initTop  = element.offsetTop;

            handle.style.cursor = 'grabbing';
            e.preventDefault();
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;

            const dx = e.clientX - startX;
            const dy = e.clientY - startY;

            let newLeft = initLeft + dx;
            let newTop  = initTop  + dy;

            // Clamp to viewport
            const clamped = constrainToViewport(newLeft, newTop, element);
            newLeft = clamped.x;
            newTop  = clamped.y;

            element.style.left   = newLeft + 'px';
            element.style.top    = newTop  + 'px';
            element.style.bottom = 'auto';
            element.style.right  = 'auto';

            uiStore.setPanelPosition(newLeft, newTop);
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                handle.style.cursor = 'grab';
                savePosition(element);
            }
        });
    }

    function constrainToViewport(x, y, element) {
        const vw     = window.innerWidth;
        const vh     = window.innerHeight;
        const width  = element.offsetWidth  || 320;
        const height = element.offsetHeight || 300;

        return {
            x: Math.max(0, Math.min(x, vw - width)),
            y: Math.max(0, Math.min(y, vh - height)),
        };
    }

    function savePosition(element) {
        const pos = { x: element.offsetLeft, y: element.offsetTop };
        uiStore.setPanelPosition(pos.x, pos.y);
        uiStore.savePanelPosition();
    }

    function loadPosition(element) {
        const pos = uiStore.loadPanelPosition();

        if (pos && (pos.x !== 0 || pos.y !== 0)) {
            element.style.left   = pos.x + 'px';
            element.style.top    = pos.y + 'px';
            element.style.bottom = 'auto';
            element.style.right  = 'auto';
        }
    }

    return { enableDrag, constrainToViewport, savePosition, loadPosition };
})();

window.dragModule = dragModule;
