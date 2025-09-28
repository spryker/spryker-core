import Component from 'ShopUi/models/component';
import { AssetEventDetail, EVENT_SELECT_ASSET } from '../asset-option/asset-option';
import MainPopup, {
    EVENT_POPUP_CONTENT_MOUNTED,
    EVENT_POPUP_OPENED,
    EVENT_POPUP_CLOSED,
    EVENT_CLOSE_POPUP,
} from 'ShopUi/components/molecules/main-popup/main-popup';
import AssetFinder from '../asset-finder/asset-finder';

export default class AssetSelector extends Component {
    protected controller = new AbortController();
    protected hiddenInput: HTMLInputElement;
    protected clearElement: HTMLElement;
    protected popup: MainPopup;
    protected selectedSerial: HTMLElement;
    protected selectedName: HTMLElement;
    protected selectedStatus: HTMLElement;
    protected mountedPopup = false;

    protected readyCallback(): void {}
    protected init(): void {
        this.hiddenInput = this.querySelector(`.${this.jsName}__input`);
        this.popup = this.querySelector(`.${this.jsName}__popup`);
        this.clearElement = this.querySelector(`.${this.jsName}__clear`);
        this.selectedSerial = this.querySelector(`.${this.jsName}__asset-serial`);
        this.selectedName = this.querySelector(`.${this.jsName}__asset-name`);
        this.selectedStatus = this.querySelector(`.${this.jsName}__asset-status`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapClearClickEvent();
        this.mapPopupEvents();
        this.replaceLinks();
    }

    protected mapClearClickEvent(): void {
        this.clearElement?.addEventListener('click', (event) => this.clearState(event));
    }

    protected mapPopupEvents(): void {
        this.popup?.addEventListener(EVENT_POPUP_CONTENT_MOUNTED, () => {
            this.finder.search(true);
            this.mountedPopup = true;
        });

        this.popup?.addEventListener(EVENT_POPUP_OPENED, (event) => {
            if (this.mountedPopup) {
                this.finder.search(true);
            }

            this.controller = new AbortController();

            const id = (event.target as HTMLElement).getAttribute('content-id');
            const element = document.getElementById(id);

            element.addEventListener(EVENT_SELECT_ASSET, (event: CustomEvent) => this.selectAsset(event.detail), {
                signal: this.controller.signal,
            });
        });

        this.popup?.addEventListener(EVENT_POPUP_CLOSED, () => {
            this.controller.abort();
            this.finder.clearResults();
        });
    }

    protected replaceLinks(): void {
        const linkClass = this.getAttribute('links-for-asset-reference');

        if (!linkClass) {
            return;
        }

        document.querySelectorAll(`.${linkClass}`).forEach((element: HTMLLinkElement) => {
            element.href = this.getReferenceUrl(this.getAttribute('asset-reference'), element.href);
        });
    }

    protected getReferenceUrl(reference: string, part = window.location.href): string {
        const base = part.includes(window.location.origin) ? undefined : window.location.origin;
        const url = new URL(part, base);
        const query = this.getAttribute('query');

        if (!query) {
            return;
        }

        if (reference) {
            url.searchParams.set(query, reference);
        } else {
            url.searchParams.delete(query);
        }

        return url.pathname + url.search + url.hash;
    }

    protected selectAsset(data: AssetEventDetail): void {
        this.fillComponent(data);
        this.popup.dispatchEvent(new CustomEvent(EVENT_CLOSE_POPUP));
        this.submitForm();
        this.finder.clear();
        this.updateHistoryUrl(data.reference);
        this.processAjaxForm();
    }

    protected clearState(event?: Event): void {
        event?.preventDefault();
        this.clearComponent();
        this.submitForm();
        this.finder.clear();
        this.updateHistoryUrl(null);
        this.processAjaxForm();
    }

    protected fillComponent(data: AssetEventDetail): void {
        const linkBase = this.getAttribute('asset-link-base');

        this.hiddenInput.value = data.reference;
        this.selectedName.textContent = data.name;
        this.selectedSerial.textContent = data.serial || '';
        this.selectedStatus.innerHTML = data.compatibilityLabel.trim() || '';

        if (linkBase) {
            (this.selectedName as HTMLLinkElement).href = linkBase + data.reference;
        }

        this.classList.add(this.selectedClass);
    }

    protected clearComponent(): void {
        this.classList.remove(this.selectedClass);
        this.hiddenInput.removeAttribute('value');
    }

    protected updateHistoryUrl(reference: string | null): void {
        if (this.hasAttribute('disable-history-url')) {
            return;
        }

        window.history.replaceState(null, '', this.getReferenceUrl(reference));
    }

    protected submitForm(): void {
        if (!this.hasAttribute('submit-closest-form')) {
            return;
        }

        if (!this.hiddenInput.value) {
            this.hiddenInput.disabled = true;
        }

        this.classList.add(this.getAttribute('disabled-class'));
        this.closest('form').submit();
    }

    protected processAjaxForm(): void {
        if (!this.hasAttribute('ajax-form')) {
            return;
        }

        this.querySelector<HTMLButtonElement>(`.${this.jsName}__ajax-trigger`)?.click();
    }

    protected get finder(): AssetFinder {
        return document.getElementById(this.getAttribute('finder-id')) as AssetFinder;
    }

    protected get selectedClass(): string {
        return this.getAttribute('selected-class');
    }
}
