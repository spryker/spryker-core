import MainServicePointFinder, {
    ProductOfferAvailability,
} from 'ServicePointWidget/components/molecules/service-point-finder/service-point-finder';
import GoogleMap, { EVENT_LOCATION_INTERACTION, MapLocation } from '../google-map/google-map';

export default class SspServicePointFinder extends MainServicePointFinder {
    protected map: GoogleMap;

    protected override init(): void {
        this.map = this.querySelector<GoogleMap>(`.${this.jsName}__map`);
        super.init();
    }

    protected override mapEvents(): void {
        super.mapEvents();
        this.mapServicePointHover('mouseover');
        this.mapServicePointHover('mouseout');

        this.addEventListener(EVENT_LOCATION_INTERACTION, (event: CustomEvent<string>) => {
            this.highlightServicePoint(event.detail);
        });
    }

    protected highlightServicePoint(id?: string): void {
        const elements = Array.from(this.querySelectorAll<HTMLElement>(`.${this.servicePointTriggerClassName}`));

        for (const element of elements) {
            const data: ProductOfferAvailability = JSON.parse(
                element.getAttribute(this.servicePointInformationAttr) || '[]',
            )[0];
            const parent = element.closest(`.${this.servicePointClass}`);

            parent.classList.remove(`${this.servicePointClass}--highlighted`);

            if (data.servicePointUuid !== id) {
                continue;
            }

            parent.classList.add(`${this.servicePointClass}--highlighted`);
            parent.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }
    }

    protected override async fetchServicePoints(): Promise<void> {
        this.ajaxProvider.queryParams.set(this.queryString, this.searchValue);
        await this.ajaxProvider.fetch();
        this.initMapLocations();
    }

    protected mapServicePointHover(action: 'mouseover' | 'mouseout'): void {
        document.addEventListener(action, (event: MouseEvent) => {
            const servicePoint = (event.target as HTMLElement).closest?.(`.${this.servicePointClass}`);

            if (!servicePoint) {
                return;
            }

            const data: ProductOfferAvailability = JSON.parse(
                servicePoint
                    .querySelector<HTMLElement>(`.${this.servicePointTriggerClassName}`)
                    ?.getAttribute(this.servicePointInformationAttr) || '[]',
            )[0];

            this.highlightServicePoint();

            if (action === 'mouseout') {
                this.map.closeInfoWindow(data.servicePointUuid);
            } else {
                this.map.openInfoWindow(data.servicePointUuid);
            }
        });
    }

    protected initMapLocations(): void {
        const data = Array.from(this.querySelectorAll<HTMLElement>(`.${this.servicePointTriggerClassName}`))
            .map(
                (element: HTMLElement) => JSON.parse(element.getAttribute(this.servicePointInformationAttr) || '[]')[0],
            )
            .reduce(
                (acc: Record<string, MapLocation>, item: ProductOfferAvailability) => ({
                    ...acc,
                    [item.servicePointUuid]: {
                        lat: Number(item.lat),
                        lng: Number(item.lng),
                        address: item.address,
                    },
                }),
                {},
            );

        this.map.initLocations(data);
    }

    protected get servicePointClass(): string {
        return this.getAttribute('service-point-class');
    }

    protected get servicePointInformationAttr(): string {
        return this.getAttribute('service-point-information-attribute');
    }
}
