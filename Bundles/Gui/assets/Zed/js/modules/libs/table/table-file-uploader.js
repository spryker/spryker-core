import { Table } from './table';

export const EVENT_DATA_TABLE_FILE_LOADED = 'EVENT_DATA_TABLE_FILE_LOADED';

/**
 * @typedef {Object} config
 * @param {string} url - The URL to upload the file to.
 * @param {string} path - (Optional) Key in the response object used to extract nested data.
 * @param {string} inputSelector - (Optional) Selector to the input element (default .js-table-file-uploader).
 */
export class TableFileUploader {
    uploader = null;
    tableIds = null;

    staticClasses = {
        ajaxInitialized: 'ajax-initialized',
        isLoading: 'is-loading',
    };

    constructor(data) {
        this.data = data;

        this.init();
    }

    init() {
        this.setDefaults();

        const selectors = document.querySelectorAll(this.data.config.inputSelector);

        this.uploader = selectors.length === 1 ? selectors[0] : this.findClosestInput();

        if (!this.uploader) return;

        this.tableIds = Array.from(document.querySelectorAll(`table[id][${Table.FEATURES.uploader.attribute}]`))
            .filter(
                (table) =>
                    JSON.parse(table.getAttribute(Table.FEATURES.uploader.attribute)).url === this.data.config.url,
            )
            .map((table) => table.id);

        this.initializeAjax();
        this.initializeUploadEvent();
    }

    setDefaults() {
        this.data.config.inputSelector ??= '.js-table-file-uploader';
    }

    initializeUploadEvent() {
        this.uploader.addEventListener(EVENT_DATA_TABLE_FILE_LOADED, (event) => {
            if (!event.detail.tableIds.includes(this.data.tableId)) {
                return;
            }

            const table = this.data.tables.get(this.data.tableId);
            const data = this.data.config.path ? event.detail.data[this.data.config.path] : event.detail.data;

            if (data?.length) {
                table.features.selectable.selectRowsByData(data);
            }
        });
    }

    initializeAjax() {
        if (this.uploader.hasAttribute(this.staticClasses.ajaxInitialized)) return;

        this.uploader.setAttribute(this.staticClasses.ajaxInitialized, true);
        this.uploader.addEventListener('change', this.fetchData.bind(this));
    }

    async fetchData(event) {
        const target = event.target;

        if (!target.value) return;

        const file = target.files[0];
        target.classList.add(this.staticClasses.isLoading);

        try {
            const formData = new FormData();
            formData.append('file', file, file.name);

            const response = await (
                await fetch(this.data.config.url, {
                    method: 'POST',
                    body: formData,
                })
            ).json();

            this.uploader.dispatchEvent(
                new CustomEvent(EVENT_DATA_TABLE_FILE_LOADED, {
                    detail: {
                        tableIds: this.tableIds,
                        data: response.data,
                    },
                }),
            );
        } catch (error) {
            // eslint-disable-next-line no-console
            console.error('Upload failed:', error);
        } finally {
            target.classList.remove(this.staticClasses.isLoading);
            target.value = '';
        }
    }

    findClosestInput() {
        let current = this.data.table;

        while (current) {
            const fileInput = current.querySelector(this.data.config.inputSelector);

            if (fileInput) return fileInput;

            current = current.parentElement;
        }

        return null;
    }
}
