import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class AssetFinder extends Component {
    protected controller = new AbortController();
    protected searchInput: HTMLInputElement;
    protected ajaxProvider: AjaxProvider;
    protected currentSearchValue = '';

    protected readyCallback(): void {}
    protected init(): void {
        this.searchInput = this.querySelector(`.${this.jsName}__search-field`);
        this.ajaxProvider = this.querySelector(`.${this.jsName}__ajax-provider`);

        this.mapEvents();
    }

    disconnectedCallback() {
        this.controller.abort();
    }

    protected mapEvents(): void {
        this.mapSearchInputEvents();
    }

    protected mapSearchInputEvents(): void {
        this.searchInput?.addEventListener(
            'keyup',
            debounce(() => this.search(), Number(this.getAttribute('debounce-delay'))),
            {
                signal: this.controller.signal,
            },
        );
    }

    protected async search(force = false): Promise<void> {
        const value = this.searchInput.value.trim();
        const isSearchLengthValid = value.length >= Number(this.getAttribute('min-letters')) || value.length === 0;

        if ((isSearchLengthValid && value !== this.currentSearchValue) || force) {
            this.currentSearchValue = value;
            await this.ajaxProvider.fetch();
        }
    }

    public clear(): void {
        this.searchInput.value = '';
        this.currentSearchValue = '';
        this.search(true);
    }
}
