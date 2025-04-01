import Component from 'ShopUi/models/component';
import MainPopup, { EVENT_CLOSE_POPUP, EVENT_POPUP_OPENED } from 'ShopUi/components/molecules/main-popup/main-popup';
import ServicePointFinder, {
    EVENT_SET_SERVICE_POINT,
    ServicePointEventDetail,
} from 'ServicePointWidget/components/molecules/service-point-finder/service-point-finder';

export default class SspServicePointSelector extends Component {
    protected noLocationContainer: HTMLElement;
    protected location: HTMLElement;
    protected locationContainer: HTMLElement;
    protected finder: ServicePointFinder;
    protected popup: MainPopup;

    protected readyCallback(): void {}
    protected init(): void {
        this.noLocationContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__no-location`)[0];
        this.location = <HTMLElement>this.getElementsByClassName(`${this.jsName}__location`)[0];
        this.locationContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__location-container`)[0];
        this.popup = <MainPopup>this.getElementsByClassName(`${this.jsName}__popup`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.popup.addEventListener(EVENT_POPUP_OPENED, this.mapFinderSetServicePointEvent.bind(this));
    }

    protected mapFinderSetServicePointEvent(): void {
        if (this.finder) {
            return;
        }

        this.finder = <ServicePointFinder>document.getElementsByClassName(this.finderClassName)[0];
        this.finder.addEventListener(EVENT_SET_SERVICE_POINT, (event: CustomEvent<ServicePointEventDetail>) =>
            this.onServicePointSelected(event.detail),
        );
    }

    protected onServicePointSelected(detail: ServicePointEventDetail): void {
        this.popup.dispatchEvent(new CustomEvent(EVENT_CLOSE_POPUP));
        this.location.innerHTML = detail.address;
        this.toggleContainer();
    }

    protected toggleContainer(): void {
        this.noLocationContainer.classList.add(this.toggleClassName);
        this.locationContainer.classList.remove(this.toggleClassName);
    }

    protected get finderClassName(): string {
        return this.getAttribute('finder-class-name');
    }

    protected get toggleClassName(): string {
        return this.getAttribute('toggle-class-name');
    }
}
