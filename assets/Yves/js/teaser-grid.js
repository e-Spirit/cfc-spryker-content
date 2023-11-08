
  // Teaser Grid
  const initTeaserGridEditor = (TPP_BROKER) => {
    document.head.appendChild(document.createElement('style')).innerHTML = `
    .tpp-icon-grid-save { background: var(--color-accent-default, #3288c3); }
    .tpp-icon-grid-save::after { -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M5.89 12.78l4.2 4.19 8.02-9.94"/></svg>') center center / cover no-repeat; }
    .tpp-icon-grid-cancel::after { -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7l10 10M7 17L17 7"/></svg>') center center / cover no-repeat; }
    .tpp-borders.tpp-grid-editor-borders { opacity: 1 !important; position: absolute !important; }
    .tpp-borders.tpp-grid-editor-borders::after { opacity: 1 !important; }
    .tpp-buttons.tpp-grid-editor-buttons { position: sticky; left: 100%; width: 1em; height: auto; }
    .tpp-grid-editor-area-proxy { position: absolute; image-rendering: pixelated; image-rendering: crisp-edges; }
    .tpp-grid-editor-item-proxy { position: absolute; z-index: 1; transition: all 300ms ease-in-out; display: grid; place-items: center; border: 1px solid rgba(50, 136, 195, 0.5); }
    .tpp-grid-editor-item-proxy[draggable="true"] { cursor: move; }
    .tpp-grid-editor-item-proxy:hover { background-color: rgba(50, 136, 195, 0.2); border: 1px solid rgba(50, 136, 195, 1); }
    .tpp-grid-editor-item-proxy.drag-over { background: repeating-linear-gradient(-45deg, rgba(50, 136, 195, 0.2), rgba(50, 136, 195, 0.2) 16px, rgba(50, 136, 195, 0) 16px, rgba(50, 136, 195, 0) 32px); border: 1px dashed rgba(50, 136, 195, 1); }
    
    .tpp-grid-editor-item-dpad { cursor: default; opacity: 0; transition: all 300ms ease-in-out; width: 88px; height: 88px; position: relative; }
    .tpp-grid-editor-item-proxy:not(.drag-over):hover .tpp-grid-editor-item-dpad { opacity: 1; }
    .tpp-grid-editor-item-dpad .ctrl { position: absolute; width: 24px; height: 24px; color: #000; cursor: pointer; }
    .tpp-grid-editor-item-dpad .ctrl:hover { color: var(--color-accent-default, #3288c3); }
    .tpp-grid-editor-item-dpad .add-row { top: 6px; left: 32px; transform: rotate(90deg); }
    .tpp-grid-editor-item-dpad .add-col { top: 29px; left: 55px; }
    .tpp-grid-editor-item-dpad .rem-row { top: 52px; left: 32px; }
    .tpp-grid-editor-item-dpad .rem-col { top: 29px; left: 9px; transform: rotate(90deg); } 
    `;
    
    const dpad = document.createElement('template');
    dpad.innerHTML = `
    <div class="tpp-grid-editor-item-dpad">
    <svg viewBox="0 0 88 88" xmlns="http://www.w3.org/2000/svg">
        <defs>
        <filter id="fs-dpad-shadow" x="0" y="0" width="88" height="88" filterUnits="userSpaceOnUse">
            <feOffset dy="3" />
            <feGaussianBlur stdDeviation="3" result="b" />
            <feFlood flood-opacity=".161" />
            <feComposite operator="in" in2="b" />
            <feComposite in="SourceGraphic" />
        </filter>
        </defs>
        <g filter="url(#fs-dpad-shadow)" fill="#fff">
        <path d="M34 76a2 2 0 01-2-2V53H11a2 2 0 01-2-2V31a2 2 0 012-2h21V8a2 2 0 012-2h20a2 2 0 012 2v21h21a2 2 0 012 2v20a2 2 0 01-2 2H56v21a2 2 0 01-2 2z" />
        </g>
        <path fill="none" d="M0 0h24v24H0z" />
        <g stroke="#3288c3" stroke-miterlimit="10" fill="none">
        <path opacity=".5" d="M40.394 37.769l8.324 8.324" />
        <path d="M46.316 38.127h4.44m-2.22 2.22v-4.44m-11.292 8.008h3.885" stroke-linecap="round" />
        </g>
    </svg>
    <div class="ctrl add-row">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-miterlimit="10" stroke="currentColor">
        <path d="M19.541 9.711v5h-15v-5zM14.492 9.348v5.372M9.492 9.348v5.372" />
        </svg>
    </div>
    <div class="ctrl add-col">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-miterlimit="10" stroke="currentColor">
        <path d="M19.541 9.711v5h-15v-5zM14.492 9.348v5.372M9.492 9.348v5.372" />
        </svg>
    </div>
    <div class="ctrl rem-row">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-miterlimit="10">
        <path stroke="rgba(0,0,0,0.2)" d="M9.711 4.459h5v15h-5zM9.71 9.508h5M9.71 14.508h5" />
        <path stroke="currentColor" d="M9.711 14.459h5v5h-5z" />
        </svg>
    </div>
    <div class="ctrl rem-col">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-miterlimit="10">
        <path stroke="rgba(0,0,0,0.2)" d="M9.711 4.459h5v15h-5zM9.71 9.508h5M9.71 14.508h5" />
        <path stroke="currentColor" d="M9.711 14.459h5v5h-5z" />
        </svg>
    </div>
    </div>
    `;
    
    const createEl = (target, className, tag = 'div') => {
    const element = target.appendChild(document.createElement(tag));
    if (className) element.className = className;
    return element;
    };
    
    const getRect = (element) => {
    const { top, left, width, height } = element.getBoundingClientRect();
    return { top: top + window.pageYOffset, left: left + window.pageXOffset, width, height };
    };
    const setRect = (element, rect) =>
    Object.entries(rect).forEach(([prop, value]) => (element.style[prop] = `${value}px`));
    
    const parsePixelStrings = (...strings) =>
    strings
        .join(' ')
        .replaceAll('px', '')
        .split(' ')
        .map((val) => +val);
    
    class GridRaster {
    constructor(grid, canvas) {
        this.grid = grid;
        this.canvas = canvas;
        this.w = canvas.width;
        this.h = canvas.height;
        this.ctx = canvas.getContext('2d');
        this.ctx.fillStyle = 'rgba(0, 0, 0, 0.8);';
    }
    update(areaRect, gridRect) {
        this.resizeCanvas(areaRect);
        const { ctx, w, h } = this;
    
        ctx.clearRect(0, 0, w, h);
    
        const { rowGap, gridTemplateRows, columnGap, gridTemplateColumns } = getComputedStyle(this.grid);
    
        const [cg, ...cols] = parsePixelStrings(columnGap, gridTemplateColumns);
        cols
        .reduce(
            (arr, val, i, { length }) => [
            ...arr,
            arr[arr.length - 1] + val,
            ...(cg && i !== length - 1 ? [arr[arr.length - 1] + val + cg] : []),
            ],
            [gridRect.left - areaRect.left]
        )
        .forEach((x) => this.verticalStorke(x, { areaRect, gridRect }));
    
        const [rg, ...rows] = parsePixelStrings(rowGap, gridTemplateRows);
        rows
        .reduce(
            (arr, val, i, { length }) => [
            ...arr,
            arr[arr.length - 1] + val,
            ...(rg && i !== length - 1 ? [arr[arr.length - 1] + val + rg] : []),
            ],
            [gridRect.top - areaRect.top]
        )
        .forEach((y) => this.horizontalStorke(y, { areaRect, gridRect }));
    }
    resizeCanvas({ width, height }) {
        if (width !== this.w || height !== this.h) {
        this.canvas.width = this.w = width;
        this.canvas.height = this.h = height;
        }
    }
    verticalStorke(x, { areaRect, gridRect }) {
        const y = gridRect.top - areaRect.top;
        const h = y + gridRect.height;
        y && this.drawLine({ x, y: 0 }, { x, y });
        this.drawLine({ x, y }, { x, y: h }, true);
        h != areaRect.height && this.drawLine({ x, y: h }, { x, y: areaRect.height });
    }
    horizontalStorke(y, { areaRect, gridRect }) {
        const x = gridRect.left - areaRect.left;
        const w = x + gridRect.width;
        x && this.drawLine({ x: 0, y }, { x, y });
        this.drawLine({ x, y }, { x: w, y }, true);
        w != areaRect.width && this.drawLine({ x: w, y }, { x: areaRect.width, y });
    }
    drawLine(p1, p2, highlight) {
        this.ctx.beginPath();
        this.ctx.strokeStyle = `rgba(0, 0, 0, ${highlight ? 0.2 : 0.1})`;
        this.ctx.lineWidth = 1;
        this.ctx.setLineDash([2, 1]);
    
        const dx = p1.x === p2.x ? 0.5 : 0;
        const dy = p1.y === p2.y ? 0.5 * (p2.x === 0 ? 1 : -1) : 0;
    
        this.ctx.moveTo(~~p1.x + dx, ~~p1.y + dy);
        this.ctx.lineTo(~~p2.x + dx, ~~p2.y + dy);
        this.ctx.stroke();
    }
    }
    
    const _ref = Symbol();
    
    const teaserGridEditor = ({ area, grid }) =>
    new Promise((resolve) => {
        const container = createEl(document.body);
        const borders = createEl(container, 'tpp-borders tpp-grid-editor-borders');
        const buttons = createEl(borders, 'tpp-buttons tpp-grid-editor-buttons');
        const cancelButton = createEl(buttons, 'tpp-button tpp-icon-grid-cancel');
        const saveButton = createEl(buttons, 'tpp-button tpp-icon-grid-save');
        const areaProxy = createEl(container, 'tpp-grid-editor-area-proxy', 'canvas');
        const items = Array.from(grid.children);
    
        const initalValues = items.map((item) => {
        if (!item.hasAttribute('data-cols')) item.dataset.cols = 6;
        if (!item.hasAttribute('data-rows')) item.dataset.rows = 1;
        const { cols, rows } = item.dataset;
        return { item, cols, rows };
        });
    
        const raster = new GridRaster(grid, areaProxy);
        let draggable = null;
    
        const swap = (a, b) => {
        if (a && b && a !== b) {
            const ax = a.parentNode.insertBefore(document.createElement('div'), a);
            const bx = b.parentNode.insertBefore(document.createElement('div'), b);
            ax.replaceWith(b);
            bx.replaceWith(a);
            drawProxies();
        }
        };
    
        const itemProxies = items.map((item) => {
        const itemProxy = createEl(container, 'tpp-grid-editor-item-proxy');
        itemProxy[_ref] = item;
    
        itemProxy.setAttribute('draggable', 'true');
        itemProxy.addEventListener('dragstart', (e) => ((draggable = item), e.dataTransfer.setDragImage(item, 0, 0)));
        itemProxy.addEventListener('dragenter', () => draggable !== item && itemProxy.classList.add('drag-over'));
        itemProxy.addEventListener('dragover', (e) => e.preventDefault());
        itemProxy.addEventListener('dragleave', () => itemProxy.classList.remove('drag-over'));
        itemProxy.addEventListener('drop', (e) => (e.stopPropagation(), swap(item, draggable)));
        itemProxy.addEventListener('dragend', () =>
            Array.from(container.querySelectorAll('.tpp-grid-editor-item-proxy.drag-over')).forEach((el) =>
            el.classList.remove('drag-over')
            )
        );
    
        itemProxy.appendChild(dpad.content.cloneNode(true));
        itemProxy.addEventListener('click', (e) => {
            const ctrl = e.target.closest('.ctrl');
            if (ctrl) {
            if (ctrl.matches('.add-row')) {
                const value = +item.dataset.rows;
                if (value < 6) item.dataset.rows = value + 1;
            } else if (ctrl.matches('.add-col')) {
                const value = +item.dataset.cols;
                if (value < 6) item.dataset.cols = value === 4 ? 6 : value + 1;
            } else if (ctrl.matches('.rem-row')) {
                const value = +item.dataset.rows;
                if (value > 1) item.dataset.rows = value - 1;
            } else if (ctrl.matches('.rem-col')) {
                const value = +item.dataset.cols;
                if (value > 2) item.dataset.cols = value === 6 ? 4 : value - 1;
            }
            }
        });
    
        return itemProxy;
        });
    
        function drawProxies() {
        const areaRect = getRect(area);
        setRect(borders, areaRect);
        itemProxies.forEach((proxy, i) => setRect(proxy, getRect(proxy[_ref])));
        setRect(areaProxy, areaRect);
        raster.update(areaRect, getRect(grid));
        }
    
        const resizingObserver = new ResizeObserver(drawProxies);
        items.forEach((item) => resizingObserver.observe(item));
    
        cancelButton.addEventListener('click', () => {
        resizingObserver.disconnect();
        initalValues.forEach(
            ({ item, cols, rows }) => (grid.appendChild(item), (item.dataset.cols = cols), (item.dataset.rows = rows))
        );
        resolve(false);
        container.remove();
        });
        saveButton.addEventListener('click', () => {
        resizingObserver.disconnect();
        resolve(true);
        container.remove();
        });
    });
    
    
    const GRID_CSS_SELECTOR = '.fs-teaser-grid-container';
    
    const BUTTON_INDEX = 3;
    
    document.head.appendChild(document.createElement('style')).innerHTML = `
    .tpp-icon-grid { background: var(--color-accent-default, #3288C3); }
    .tpp-icon-grid::after { -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M5 5h14v14H5zM9.65 5.102v13.796M14.289 5.102v13.796M18.867 9.68H5.071M18.867 14.32H5.071"/></svg>') center center / cover no-repeat; }
    `;
    
    const isGridContainer = ({ $node, previewId, status: { elementType } }) => {
    if (previewId[0] !== '#' && elementType === 'Section') {
        if ($node.matches(GRID_CSS_SELECTOR)) return $node;
        const grid = $node.querySelector(GRID_CSS_SELECTOR);
        const area = grid && grid.closest('[data-preview-id]:not([data-preview-id^="#"])');
        if (area && area.dataset.previewId === previewId) return grid;
    }
    };
    
    const isGridItem = ({ $node, previewId }) => /^#[0-9]+$/.test(previewId) && $node.closest(GRID_CSS_SELECTOR);
    
    const getGridNodes = (scopeOrGrid) => {
    const grid = '$node' in scopeOrGrid ? isGridItem(scopeOrGrid) || isGridContainer(scopeOrGrid) : scopeOrGrid;
    const area = grid && grid.closest('[data-preview-id]:not([data-preview-id^="#"])');
    return area ? { grid, area, exists: !!area.querySelector('[data-preview-id="#0"]') } : { exists: false };
    };
    
    const saveGridChanges = async (scope) => {
    const { grid, area } = getGridNodes(scope);
    if (area) {
        const catalogName = area.querySelector('[data-preview-id^="#"]').dataset.previewId.substring(1);
        const items = Array.from(grid.children)
        .filter((item) => item.matches('[data-preview-id^="#"]'))
        .map((item, i) => {
            const { cols, rows, previewId } = item.dataset;
            return { cols, rows, index: previewId.substring(1), reorder: `${i}` };
        })
        .sort((a, b) => a.index - b.index);
    
        const { id: sectionId, language } = scope.status;
        disable(area);
        await TPP_BROKER.execute('script:tpp_teaser_grid_update', { sectionId, language, catalogName, items });
        TPP_BROKER.triggerChange(area.dataset.previewId);
        enable(area);
    }
    };

  TPP_BROKER.registerButton(
    {
      css: 'tpp-icon-grid',
      supportsComponentPath: true,
      isVisible: (scope) => getGridNodes(scope).exists,
      isEnabled: ({ status: { permissions = {} } = {} }) => permissions.change,
      execute: async (scope) => (await teaserGridEditor(getGridNodes(scope))) && saveGridChanges(scope),
    },
    BUTTON_INDEX
  );
};



FCECOM.addHook('previewInitialized', async ({TPP_BROKER}) => {
  initTeaserGridEditor(TPP_BROKER);
});