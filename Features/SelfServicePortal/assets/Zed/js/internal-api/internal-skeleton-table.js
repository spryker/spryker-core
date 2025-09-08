import { SELECTABLE_TABLE_REMOVE_ALL, SELECTABLE_TABLE_CHANGED } from 'ZedGuiModules/libs/table/selectable-table';

export class InternalSkeletonTable {
    skeleton = null;
    tableId = null;

    constructor(data) {
        this.data = data;

        this.init();
    }

    init() {
        this.skeleton = this.data.table.closest('.tabs-container').querySelector('.js-table-skeleton');
        this.tableId = this.skeleton.getAttribute('data-table-id');

        this.setRemoveAllButton();
        this.setTableChanging();
    }

    setRemoveAllButton() {
        this.skeleton.querySelector('.js-table-skeleton-remove-all').addEventListener('click', () => {
            document.dispatchEvent(new CustomEvent(SELECTABLE_TABLE_REMOVE_ALL, { detail: { id: this.tableId } }));
        });
    }

    setTableChanging() {
        const dynamicContent = this.skeleton.querySelector('.js-table-skeleton-dynamic-counter');

        this.data.table.addEventListener(SELECTABLE_TABLE_CHANGED, (event) => {
            const { skeletonId, selected } = event.detail;

            if (skeletonId === this.tableId && dynamicContent) {
                dynamicContent.innerHTML = selected.length;
            }
        });
    }
}
