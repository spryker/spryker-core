/**
 * @typedef {Object} config
 * @param {string} wrapperSelector - Wrapper selector which contains filters and table (default: '.panel-body, .ibox-content').
 * @param {string} filterFieldSelector - Selector for the field element (default: .js-filterable-table-field).
 */
export class FilterableTable {
    data = {};
    filters = {};

    constructor(data) {
        this.data = data;

        this.init();
    }

    init() {
        this.setDefaults();
        this.clearStorage();
        this.setPredefinedData();
        this.setupFilters();
    }

    setDefaults() {
        this.data.config.wrapperSelector ??= '.panel-body, .ibox-content';
        this.data.config.filterFieldSelector ??= '.js-filterable-table-field';
    }

    setPredefinedData() {
        this.filters = JSON.parse(localStorage.getItem(this.getStorageKey()) || '{}');
    }

    setupFilters() {
        const { table, config } = this.data;
        const wrapper = table.closest(config.wrapperSelector);

        if (!wrapper) return;

        const filters = wrapper.querySelectorAll(config.filterFieldSelector) ?? [];

        for (const filter of filters) {
            const type = filter.tagName === 'SELECT' ? 'change' : 'input';
            const value = this.filters[filter.name];

            this.filterAction(filter, type);

            if (value === undefined || (typeof value === 'string' && !value.trim())) {
                continue;
            }

            filter.value = this.filters[filter.name];
            filter.dispatchEvent(new Event(type, { bubbles: true }));
        }
    }

    filterAction(filter, type) {
        let timeoutId = 0;
        const colId = filter.getAttribute('data-col-id');
        const index = this.data.api.settings()[0].aoColumns.find((col) => col.nTh.id === colId)?.idx;

        filter.addEventListener(type, (event) => {
            clearTimeout(timeoutId);

            timeoutId = setTimeout(() => {
                const value = event.target.value;

                this.filters[filter.name] = value;
                this.data.api.settings()[0].jqXHR.abort();
                this.data.api.column(Number(index)).search(value).draw();
                this.saveItemsToStorage();
            }, this.data.options.debounce);
        });
    }

    clearStorage() {
        const navType = performance.getEntriesByType('navigation')[0].type;

        if (navType === 'navigate') {
            localStorage.removeItem(this.getStorageKey());
        }
    }

    saveItemsToStorage() {
        localStorage.setItem(this.getStorageKey(), JSON.stringify(this.filters));
    }

    getStorageKey() {
        return `${this.data.tableId}-filters`;
    }
}
