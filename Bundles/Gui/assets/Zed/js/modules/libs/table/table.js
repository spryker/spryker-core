import { SelectableTable } from './selectable-table';
import { FilterableTable } from './filterable-table';
import { TableFileUploader } from './table-file-uploader';

function getTranslations() {
    const localeEl = document.documentElement.dataset.applicationLocale;
    const locale = typeof localeEl === 'string' ? localeEl.split('_')[0].split('-')[0] : 'en';
    let obj;

    try {
        obj = require('../i18n/' + locale + '.json');
    } catch {
        obj = require('../i18n/en.json');
    }

    return obj;
}

export class Table {
    static FEATURES = {
        selectable: {
            class: SelectableTable,
            attribute: 'data-selectable',
        },
        filterable: {
            class: FilterableTable,
            attribute: 'data-filterable',
        },
        uploader: {
            attribute: 'data-uploader',
            class: TableFileUploader,
        },
    };

    static #defaultOptions = {
        selectors: '.gui-table-data[id],.gui-table-data-no-search[id]',
        config: {
            debounce: null,
        },
        configuration: {
            default: {
                scrollX: true,
                language: getTranslations(),
                dom:
                    "<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'alt-row'<'alt-row__left'l><'alt-row__center'p>>",
            },
            noSearch: {
                bFilter: false,
                bInfo: false,
                scrollX: true,
            },
        },
    };

    tables = new Map();

    constructor(options = {}) {
        this.options = { ...Table.#defaultOptions, ...options };
        this.options.config.debounce ??= 1000;
        this.init();
    }

    init() {
        this.initiateSideEffects();

        const tables = document.querySelectorAll(this.options.selectors) ?? [];

        for (const table of tables) {
            const configuration = table.classList.contains('gui-table-data-no-search')
                ? this.options.configuration.noSearch
                : this.options.configuration.default;
            const api = $.fn.dataTable.isDataTable(table)
                ? $(table).DataTable({ retrieve: true })
                : $(table).DataTable({ retrieve: true, ...configuration });
            const data = {
                table,
                api,
                tableId: table.id,
                options: this.options.config,
                translations: this.options.configuration.default.language,
            };

            this.initFeatures(data);
        }
    }

    initFeatures(data) {
        const features = {};

        for (const [name, feature] of Object.entries(Table.FEATURES)) {
            const { attribute, class: FeatureClass } = feature;

            if (!data.table.hasAttribute(attribute) && attribute) {
                continue;
            }

            const params = {
                ...data,
                features,
                config: JSON.parse(data.table.getAttribute(attribute) || '{}'),
                tables: this.tables,
            };

            this.tables.set(data.tableId, params);

            /**
             * @param {Object} element - Table element.
             * @param {Object} api - Table API instance.
             * @param {Object} config - Config for feature.
             * @param {Object} features - Instances of features on current table.
             * @param {string} tableId - Table ID.
             * @param {Object} options - Table options.
             * @param {Object} tables - Map of initialized tables.
             * @param {Object} translations - List of translations for current locale.
             */
            features[name] = new FeatureClass(params);
        }
    }

    initiateSideEffects() {
        document.addEventListener('TABS-CHANGE-EVENT', (event) => {
            const id = event.detail.id;

            if (!id) return;

            const tables = document.querySelector(id).querySelectorAll(this.options.selectors);

            for (const table of tables) {
                const api = $(table).DataTable({ retrieve: true });

                api.columns.adjust();
                api.responsive?.recalc();
                api.fixedHeader?.adjust();
            }
        });
    }
}
