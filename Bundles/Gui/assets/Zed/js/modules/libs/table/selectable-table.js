export const SELECTABLE_TABLE_REMOVE_ALL = 'SELECTABLE-TABLE-REMOVE-ALL';
export const SELECTABLE_TABLE_CHANGED = 'SELECTABLE-TABLE-CHANGED';

/**
 * @typedef {Object} config
 * @param {string} moveToSelector - Selector for the element to move selected products to.
 * @param {string} checkboxSelector - (Optional) Selector for the checkbox element (default: .js-selectable-table-checkbox).
 * @param {string} inputSelector - Selector for the input element.
 * @param {string} counterHolderSelector - Holder for the counter label.
 * @param {string} colId - Column ID for comparison.
 * @param {string} colSelection - (Optional) Column ID for the selection checkbox (default: action).
 */
export class SelectableTable {
    selected = [];
    data = {};
    colIndexes = {};
    skeletonTable = null;

    staticClasses = {
        remove: 'js-remove-item',
        counter: 'js-counter',
        selected: 'has-selected',
    };

    constructor(data) {
        this.data = data;

        this.init();
    }

    init() {
        this.setDefaults();
        this.clearStorage();
        this.setPredefinedData();
        this.setupTable();
        this.removeRowAction();
        this.addCounter();
        this.setRemoveAllAction();
    }

    setDefaults() {
        this.data.config.checkboxSelector ??= '.js-selectable-table-checkbox';
        this.data.config.colSelection ??= 'action';
    }

    setupTable() {
        this.skeletonTable = document.querySelector(this.data.config.moveToSelector);

        this.data.api.on('draw', () => {
            this.updateCheckboxes();
            this.updateInput();
            this.updateCounter();
        });
    }

    setRemoveAllAction() {
        document.addEventListener(SELECTABLE_TABLE_REMOVE_ALL, (event) => {
            if ([this.data.tableId, this.skeletonTable?.id].includes(event.detail.id)) {
                this.removeAll();
            }
        });
    }

    removeAll() {
        this.selected = [];

        for (const checkbox of this.data.table.querySelectorAll(this.data.config.checkboxSelector) ?? []) {
            checkbox.checked = false;
        }

        this.tableActions();
    }

    setPredefinedData() {
        const cols = this.data.api.columns().header().toArray();

        this.colIndexes = {
            id: cols.findIndex((header) => header.id === this.data.config.colId),
            selection: cols.findIndex((header) => header.id === this.data.config.colSelection),
        };

        const store = JSON.parse(localStorage.getItem(this.getStorageKey()) || '[]');
        const input = document.querySelector(this.data.config.inputSelector);
        const data = JSON.parse((input.value || '[]').replace(/&quot;/g, '"').replace(/,/g, ''));
        const merged = [...store, ...data];
        const unique = merged.filter(
            (item, index, self) =>
                index === self.findIndex((other) => other[this.colIndexes.id] === item[this.colIndexes.id]),
        );

        input.value = '';
        this.selected = unique;
    }

    removeRowAction() {
        document.addEventListener('click', (event) => {
            const link = event.target.closest(`.${this.staticClasses.remove}`);
            const isCurrentTable = link?.closest('table[id]')?.id === this.skeletonTable?.id;

            if (!link?.dataset.id || !isCurrentTable) return;

            event.preventDefault();
            this.removeRow(link.dataset.id);
        });
    }

    updateCheckboxes() {
        const checkboxes = this.data.table.querySelectorAll(this.data.config.checkboxSelector);

        if (!checkboxes.length) return;

        for (const checkbox of checkboxes) {
            const row = checkbox.closest('tr');
            const rowData = this.data.api.rows(row).data()[0];
            const id = rowData[this.colIndexes.id];

            if (this.selected.some((item) => item[this.colIndexes.id] === id)) {
                checkbox.checked = true;
                this.addRow(rowData, true);
            }

            checkbox.addEventListener('change', () => {
                checkbox.checked ? this.addRow(rowData) : this.removeRow(id);
            });
        }
    }

    addRow(rowData, skip = false) {
        const item = [...rowData];
        !skip && this.selected.push(item);
        this.tableActions();
    }

    removeRow(id) {
        const idx = this.selected.findIndex((item) => String(item[this.colIndexes.id]) === String(id));

        if (idx === -1) return;

        const rowIndex = this.data.api.rows((_, data) => String(data[this.colIndexes.id]) === String(id)).indexes()[0];
        this.data.api.row(rowIndex).node().querySelector(this.data.config.checkboxSelector).checked = false;
        this.selected.splice(idx, 1);
        this.tableActions();
    }

    tableActions() {
        this.saveItemsToStorage();
        this.renderSelectedItemsTable();
        this.updateInput();
        this.updateCounter();
        this.skeletonModification();
        this.dispatchTableActions();
    }

    dispatchTableActions() {
        this.data.table.dispatchEvent(
            new CustomEvent(SELECTABLE_TABLE_CHANGED, {
                detail: {
                    id: this.data.tableId,
                    skeletonId: this.skeletonTable.id,
                    selected: this.selected,
                },
                bubbles: true,
            }),
        );
    }

    skeletonModification() {
        if (!this.selected.length) {
            this.skeletonTable.classList.remove(this.staticClasses.selected);
        } else {
            this.skeletonTable.classList.add(this.staticClasses.selected);
        }
    }

    renderSelectedItemsTable() {
        const selected = this.selected.map((rowData) => {
            const item = [...rowData];
            item.splice(this.colIndexes.selection, 1);
            item.push(this.getRemoveButtonTemplate(rowData));

            return item;
        });

        $(this.skeletonTable).DataTable({ retrieve: true }).clear().rows.add(selected).draw();
    }

    updateInput() {
        const input = document.querySelector(this.data.config.inputSelector);

        if (!input) return;

        input.value = this.selected.map((item) => item[this.colIndexes.id]).join(',');
    }

    updateCounter() {
        if (!this.data.config.counterHolderSelector) return;

        const counter = document.querySelector(
            `${this.data.config.counterHolderSelector} .${this.staticClasses.counter}`,
        );

        if (!counter) return;

        counter.innerHTML = this.selected.length ? ` (${this.selected.length})` : '';
    }

    addCounter() {
        if (!this.data.config.counterHolderSelector) return;
        document
            .querySelector(this.data.config.counterHolderSelector)
            ?.insertAdjacentHTML('beforeend', this.getCounterTemplate());
    }

    saveItemsToStorage() {
        localStorage.setItem(this.getStorageKey(), JSON.stringify(this.selected));
    }

    clearStorage() {
        const navType = performance.getEntriesByType('navigation')[0].type;

        if (navType === 'navigate') {
            localStorage.removeItem(this.getStorageKey());
        }
    }

    getStorageKey() {
        return `${this.data.tableId}-selected-items`;
    }

    getRemoveButtonTemplate(data) {
        return `<a
            href="#"
            data-id="${data[this.colIndexes.id]}"
            class="${this.staticClasses.remove} btn-xs"
        >
            ${this.data.translations.remove}
        </a>`;
    }

    getCounterTemplate() {
        return `<span class="${this.staticClasses.counter}"></span>`;
    }
}
