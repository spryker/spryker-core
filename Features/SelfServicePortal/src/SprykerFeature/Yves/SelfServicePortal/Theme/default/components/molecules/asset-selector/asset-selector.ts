import Component from 'ShopUi/models/component';
import { AssetEventDetail, EVENT_SELECT_ASSET } from '../asset-option/asset-option';
import MainPopup from 'ShopUi/components/molecules/main-popup/main-popup';
import AssetFinder from '../asset-finder/asset-finder';

export default class AssetSelector extends Component {
    protected controller = new AbortController();
    protected hiddenInput: HTMLInputElement;
    protected clearElement: HTMLElement;
    protected popup: MainPopup;
    protected selectedSerial: HTMLElement;
    protected selectedName: HTMLElement;
    protected selectedStatus: HTMLElement;

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
        this.popup?.addEventListener('popupOpened', (event) => {
            this.controller = new AbortController();

            const id = (event.currentTarget as HTMLElement).getAttribute('content-id');
            const element = document.getElementById(id);

            element.addEventListener(EVENT_SELECT_ASSET, (event: CustomEvent) => this.selectAsset(event.detail), {
                signal: this.controller.signal,
            });
        });

        this.popup?.addEventListener('popupClosed', () => {
            this.controller.abort();
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
        this.hiddenInput.value = data.reference;
        this.selectedName.textContent = data.name;
        this.selectedSerial.textContent = data.serial || '';
        this.selectedStatus.innerHTML = data.compatibilityLabel.trim() || '';
        this.classList.add(this.selectedClass);
        this.popup.dispatchEvent(new CustomEvent('closePopup'));
        this.submitForm();
        this.clearFinder();
        window.history.replaceState(null, '', this.getReferenceUrl(data.reference));
    }

    protected clearState(event?: Event): void {
        event?.preventDefault();
        this.classList.remove(this.selectedClass);
        this.hiddenInput.removeAttribute('value');
        this.submitForm();
        this.clearFinder();
        window.history.replaceState(null, '', this.getReferenceUrl(null));
    }

    protected clearFinder(): void {
        const finderId = this.getAttribute('finder-id');
        (document.getElementById(finderId) as AssetFinder)?.clear();
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

    protected get selectedClass(): string {
        return this.getAttribute('selected-class');
    }
}
