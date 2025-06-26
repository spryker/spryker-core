import Component from 'ShopUi/models/component';

export const EVENT_SELECT_ASSET = 'selectAsset';

export interface AssetEventDetail {
    id: string;
    name: string;
    reference: string;
    serial?: string;
}

export default class AssetList extends Component {
    protected readyCallback(): void {}

    protected init(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.querySelectorAll(`.${this.jsName}__item`)?.forEach((item: HTMLElement) => {
            item.addEventListener('click', this.itemDispatchInformation.bind(this));
            item.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    this.itemDispatchInformation(event);
                }
            });
        });
    }

    protected itemDispatchInformation(event: Event): void {
        const detail = JSON.parse((event.currentTarget as HTMLElement).getAttribute('data-information'));

        this.dispatchCustomEvent(EVENT_SELECT_ASSET, detail, {
            bubbles: true,
        });
    }
}
