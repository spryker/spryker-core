import { SELECTABLE_TABLE_REMOVE_ALL, SELECTABLE_TABLE_CHANGED } from 'ZedGuiModules/libs/table/selectable-table';

export class InternalGuardTabsState {
    selector = '.js-guard-tabs';
    currentTab = null;
    input = null;
    tabUrls = null;
    dirtyElements = [];

    constructor() {
        this.init();
    }

    init() {
        const tabs = document.querySelector(this.selector);
        this.input = document.querySelector(tabs.getAttribute('data-input-selector'));
        this.tabUrls = document.querySelectorAll(
            `${this.selector} .nav-tabs:not(.tab-content .nav-tabs) a[data-toggle="tab"]`,
        );

        this.setTabs();
        this.setAcceptGuardTab();
        this.setActionsOnValueChange();
    }

    setActionsOnValueChange() {
        const submitButton = document.querySelector('.js-guard-submit');

        document.addEventListener(SELECTABLE_TABLE_CHANGED, (event) => {
            if (event.detail.selected.length) {
                this.dirtyElements.push(event.detail.id);
            } else {
                this.dirtyElements = this.dirtyElements.filter((id) => id !== event.detail.id);
            }

            submitButton.disabled = !this.dirtyElements.length;
        });

        document.querySelectorAll('.js-form-file').forEach((holder) => {
            const fileInput = holder.querySelector('.js-file-input');
            const removeFile = holder.querySelector('.js-form-remove-file');

            fileInput.addEventListener('change', (event) => {
                if (event.target.value) {
                    event.target.classList.add('has-file');
                    this.dirtyElements.push(event.target.id);
                } else {
                    event.target.classList.remove('has-file');
                    this.dirtyElements = this.dirtyElements.filter((id) => id !== event.target.id);
                }

                submitButton.disabled = !this.dirtyElements.length;
            });

            removeFile.addEventListener('click', (event) => {
                event.preventDefault();
                fileInput.value = '';
                fileInput.classList.remove('has-file');
                fileInput.dispatchEvent(new Event('change'));
            });
        });
    }

    setTabs() {
        this.tabUrls.forEach((tab) => {
            tab.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();

                this.currentTab = tab;

                if (this.dirtyElements.length) {
                    this.showGuard();
                } else {
                    this.processTab();
                }
            });

            if (window.spryker?.isBootstrapVersionLatest) {
                tab.addEventListener('shown.bs.tab', (event) => {
                    this.fillInput(event.target);
                });
            }
        });

        if (!window.spryker?.isBootstrapVersionLatest) {
            $(this.tabUrls).on('shown.bs.tab', (e) => {
                this.fillInput(e.target);
            });
        }

        this.fillInput();
    }

    showGuard() {
        const id = '#tabs-guard-popup';

        if (window.spryker?.isBootstrapVersionLatest) {
            const bootstrap = window.spryker.bootstrap;
            const modal = bootstrap.Modal(document.getElementById(id));
            modal.show();
        } else {
            $(id).modal('show');
        }
    }

    setAcceptGuardTab() {
        document.querySelector('.js-guard-tabs-accept').addEventListener('click', () => {
            for (const tableId of this.dirtyElements) {
                document.dispatchEvent(new CustomEvent(SELECTABLE_TABLE_REMOVE_ALL, { detail: { id: tableId } }));
            }

            this.processTab();
        });
    }

    processTab() {
        const instance = this.currentTab.closest('.tabs-container').tabsInstance;
        instance.activateTab($(this.currentTab), this.currentTab.getAttribute('href'));
        this.currentTab = null;
        this.dirtyElements = [];
    }

    getChangedTables() {
        const activeTab = Array.from(this.tabUrls)
            .find((tab) => tab.closest('.active'))
            .getAttribute('href');

        return Array.from(document.querySelectorAll(`${activeTab} .js-selectable-table-skeleton[id]`)).reduce(
            (acc, skeleton) => {
                return $(skeleton).DataTable().data().any() ? [...acc, skeleton.id] : acc;
            },
            [],
        );
    }

    fillInput(tab) {
        if (!this.input) return;

        tab ??= Array.from(this.tabUrls).find((tab) => tab.closest('.active'));

        const currentValue = tab.getAttribute('href')?.replace('#tab-content-', '');
        this.input.value = currentValue;
    }
}
