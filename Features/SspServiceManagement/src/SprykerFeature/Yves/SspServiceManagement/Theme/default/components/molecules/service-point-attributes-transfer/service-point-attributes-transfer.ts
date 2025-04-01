import Component from 'ShopUi/models/component';

interface TransferLocator {
    selector: string;
    attribute: string;
}

interface TransferableData {
    from: TransferLocator;
    to: TransferLocator;
    json: {
        index: number;
        prop: string;
    };
}

export default class ServicePointAttributesTransfer extends Component {
    protected readyCallback(): void {}
    protected init(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        document.addEventListener('click', this.transferData.bind(this));
    }

    protected transferData(event: Event): void {
        const target = event.target as HTMLElement;

        if (!target.closest(`.${this.triggerClassName}`)) {
            return;
        }

        for (const data of this.transferableData) {
            const from = document.querySelector(data.from.selector);
            const to = document.querySelector(data.to.selector);

            if (data.json) {
                const json = from.getAttribute(data.from.attribute);
                to.setAttribute(
                    typeof data.to === 'string' ? data.to : data.to.attribute,
                    JSON.parse(json)[data.json.index][data.json.prop],
                );
            }
        }
    }

    protected get transferableData(): TransferableData[] {
        return JSON.parse(this.getAttribute('transferable-data'));
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }
}
