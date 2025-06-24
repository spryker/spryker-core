import Component from 'ShopUi/models/component';
import { EVENT_SCRIPT_LOAD } from 'ShopUi/components/molecules/script-loader/script-loader';

export interface Coordinates {
    lat: number;
    lng: number;
}

export interface MapLocation extends Coordinates {
    address: string;
}

export const EVENT_LOCATION_INTERACTION = 'google-map-click-location-interaction';

export default class GoogleMap extends Component {
    protected DEFAULT_MIN_ZOOM = 12;

    protected map: GoogleMapInstance;
    protected activeInfoWindow: GoogleInfoWindowInstance;
    protected markers: Record<string, GoogleMarkerInstance> = {};
    protected infoWindows: Record<string, GoogleInfoWindowInstance> = {};

    protected readyCallback(): void {}
    protected init(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        if (window.google?.maps) {
            this.initMap();

            return;
        }

        this.querySelector(`.${this.jsName}__script-loader`).addEventListener(
            EVENT_SCRIPT_LOAD,
            this.initMap.bind(this),
            { once: true },
        );
    }

    protected initMap(): void {
        this.map = new window.google.maps.Map(this.querySelector(`.${this.jsName}__container`), {
            center: this.center,
            zoom: 0,
            zoomControl: false,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
        });

        this.initLocations(this.locations);

        document.addEventListener('click', (event: MouseEvent) => {
            const isInsideMap = (event.target as HTMLElement).closest(`.${this.jsName}__container`);

            if (!isInsideMap) {
                this.closeActiveInfoWindow();
            }
        });
    }

    initLocations(locations: Record<string, MapLocation>): void {
        if (!locations) {
            return;
        }

        if (!this.map) {
            return;
        }

        for (const marker of Object.values(this.markers)) {
            marker.setMap(null);
        }

        this.markers = {};
        this.infoWindows = {};

        const bounds = new window.google!.maps.LatLngBounds();

        for (const [id, loc] of Object.entries(locations)) {
            const marker = new window.google.maps.Marker({
                position: loc,
                map: this.map,
                title: loc.address,
                icon: this.marker,
            });

            const infoWindow = new window.google.maps.InfoWindow({
                content: loc.address,
            });

            this.markers[id] = marker;
            this.infoWindows[id] = infoWindow;

            bounds.extend(loc);

            marker.addListener('click', () => {
                this.openInfoWindow(id);
                this.dispatchCustomEvent(EVENT_LOCATION_INTERACTION, id, { bubbles: true });
            });
        }

        this.map.fitBounds(bounds);

        window.google!.maps.event.addListener(this.map, 'click', () => {
            this.closeActiveInfoWindow();
        });

        window.google!.maps.event.addListenerOnce(this.map, 'bounds_changed', () => {
            if (this.map.getZoom() > this.DEFAULT_MIN_ZOOM) {
                this.map.setZoom(this.DEFAULT_MIN_ZOOM);
            }
        });
    }

    protected closeActiveInfoWindow(): void {
        this.activeInfoWindow?.close();
        this.activeInfoWindow = null;
        this.dispatchCustomEvent(EVENT_LOCATION_INTERACTION, null, { bubbles: true });
    }

    openInfoWindow(id: string): void {
        const marker = this.markers[id];
        const infoWindow = this.infoWindows[id];

        if (!marker || !infoWindow) {
            return;
        }

        this.activeInfoWindow?.close();
        infoWindow.open(this.map, marker);
        this.activeInfoWindow = infoWindow;
    }

    closeInfoWindow(id: string): void {
        const infoWindow = this.infoWindows[id];

        if (!infoWindow) {
            return;
        }

        infoWindow.close();
        this.activeInfoWindow = null;
    }

    protected get center(): Coordinates {
        return JSON.parse(this.getAttribute('center') || null);
    }

    protected get marker(): string | GoogleSymbolIcon {
        return JSON.parse(this.getAttribute('marker') || null);
    }

    protected get locations(): Record<string, MapLocation> {
        return JSON.parse(this.getAttribute('locations') || null);
    }
}

declare global {
    interface GoogleMapOptions {
        center: Coordinates;
        zoom: number;
        disableDefaultUI?: boolean;
        zoomControl?: boolean;
        mapTypeControl?: boolean;
        streetViewControl?: boolean;
        fullscreenControl?: boolean;
        rotateControl?: boolean;
        scaleControl?: boolean;
        keyboardShortcuts?: boolean;
    }

    interface GoogleLatLngBounds {
        extend(position: { lat: number; lng: number }): void;
    }

    interface GoogleMapInstance {
        panTo(position: { lat: number; lng: number }): void;
        setCenter(position: { lat: number; lng: number }): void;
        fitBounds(bounds: GoogleLatLngBounds): void;
        setZoom(zoom: number): void;
        getZoom(): number;
    }

    interface GoogleSymbolIcon {
        path: string | number;
        scale?: number;
        fillColor?: string;
        fillOpacity?: number;
        strokeColor?: string;
        strokeOpacity?: number;
        strokeWeight?: number;
        rotation?: number;
        anchor?: { x: number; y: number };
    }

    interface GoogleMarkerOptions {
        position: { lat: number; lng: number };
        map: GoogleMapInstance;
        title?: string;
        icon?: string | GoogleSymbolIcon;
    }

    interface GoogleMarkerInstance {
        setMap(map: GoogleMapInstance | null): void;
        addListener(eventName: string, handler: () => void): void;
        getPosition(): { lat(): number; lng(): number };
    }

    interface GoogleInfoWindowInstance {
        open(map?: GoogleMapInstance, anchor?: GoogleMarkerInstance): void;
        close(): void;
        setContent(content: string | Node): void;
        getContent(): string | Node;
        setPosition(position: { lat: number; lng: number }): void;
        getPosition(): { lat(): number; lng(): number } | null;
    }

    interface Window {
        google?: {
            maps: {
                Map: new (element: HTMLElement, options: GoogleMapOptions) => GoogleMapInstance;
                Marker: new (options: GoogleMarkerOptions) => GoogleMarkerInstance;
                LatLng: new (lat: number, lng: number) => { lat(): number; lng(): number };
                InfoWindow: new (options?: { content?: string }) => GoogleInfoWindowInstance;
                LatLngBounds: new () => GoogleLatLngBounds;
                SymbolPath: {
                    CIRCLE: number; // 0
                    BACKWARD_CLOSED_ARROW: number; // 1
                    BACKWARD_OPEN_ARROW: number; // 2
                    FORWARD_CLOSED_ARROW: number; // 3
                    FORWARD_OPEN_ARROW: number; // 4
                };
                event: {
                    addListenerOnce: (map: GoogleMapInstance, eventName: string, handler: () => void) => void;
                    addListener: (map: GoogleMapInstance, eventName: string, handler: () => void) => void;
                };
            };
        };
    }
}
