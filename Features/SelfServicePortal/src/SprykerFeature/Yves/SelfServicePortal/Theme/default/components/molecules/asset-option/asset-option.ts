import Component from 'ShopUi/models/component';

export const EVENT_SELECT_ASSET = 'selectAsset';

export interface AssetEventDetail {
    id: string;
    name: string;
    reference: string;
    serial?: string;
    compatibilityLabel?: string;
}

export default class AssetOption extends Component {
    protected readyCallback(): void {}

    protected init(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        const trigger = this.querySelector(`.${this.jsName}__trigger`);

        if (!trigger) return;

        trigger.addEventListener('click', this.dispatchAsset.bind(this));
        trigger.addEventListener('keydown', (event: KeyboardEvent) => {
            if (event.key === 'Enter') {
                this.dispatchAsset();
            }
        });
    }

    protected dispatchAsset(): void {
        const detail = JSON.parse(this.getAttribute('asset'));

        this.dispatchCustomEvent(EVENT_SELECT_ASSET, detail, {
            bubbles: true,
        });
    }
}
