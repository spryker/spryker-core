import Component from 'ShopUi/models/component';

export default class ServicePointShipmentTypes extends Component {
    protected readyCallback(): void {}
    protected init(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        for (const radio of Array.from(this.querySelectorAll(`.${this.jsName}__radio input`))) {
            radio.addEventListener('change', this.toggle.bind(this));
        }
    }

    protected toggle(event: Event): void {
        const target = event.target as HTMLInputElement;

        if (this.noServiceTypes.includes(target.value)) {
            event.stopPropagation();
            event.stopImmediatePropagation();
            this.querySelector(`.${this.ajaxContainerClass}`).innerHTML = '';
        }
    }

    protected get ajaxContainerClass(): string {
        return this.getAttribute('ajax-container-class');
    }

    protected get noServiceTypes(): string[] {
        return JSON.parse(this.getAttribute(`no-service-types`));
    }
}
