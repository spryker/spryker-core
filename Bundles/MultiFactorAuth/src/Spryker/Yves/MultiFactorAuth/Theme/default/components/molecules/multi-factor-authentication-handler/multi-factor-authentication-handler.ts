import Component from 'ShopUi/models/component';
import MainPopup, { EVENT_POPUP_CLOSED, EVENT_CLOSE_POPUP } from 'ShopUi/components/molecules/main-popup/main-popup';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

interface AuthResponse {
    requiresAdditionalAuth?: boolean;
}

export default class MultiFactorAuthenticationHandler extends Component {
    protected ajaxProvider: AjaxProvider;
    protected ajaxProviderDynamic: AjaxProvider;
    protected popup: MainPopup;
    protected popupContent: HTMLElement;
    protected navigation: HTMLElement;
    protected form: HTMLFormElement;
    protected abortController: AbortController;
    protected boundInterceptForm: EventListener;

    protected readonly CONTENT_SELECTOR = '.multi-factor-authentication-content';
    protected readonly CLOSE_POPUP_ATTR = 'data-close-popup';
    protected readonly ERROR_ATTR = 'data-error';
    protected readonly SINGLE_CLICK_BTN = 'button[data-init-single-click]';
    protected readonly JS_MULTI_FACTOR_AUTHENTICATION_HANDLER_FIELD = '[multi_factor_auth_enabled]';

    protected readyCallback(): void {}

    protected init(): void {
        this.popup = <MainPopup>this.getElementsByClassName(`${this.jsName}__popup`)[0];
        this.popupContent = this.getElementsByClassName(`${this.jsName}__popup-content`)[0];
        this.navigation = this.getElementsByClassName(`${this.jsName}__navigation`)[0];
        this.form = <HTMLFormElement>document.querySelector(this.formSelector);
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__ajax-provider`)[0];
        this.ajaxProviderDynamic = <AjaxProvider>(
            this.getElementsByClassName(`${this.jsName}__ajax-provider-dynamic`)[0]
        );
        this.abortController = new AbortController();

        if (this.isJsEnabledHandler) {
            this.addJsEnabledValidationTokens();
        }
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.form.addEventListener('submit', this.interceptForm.bind(this));
    }

    protected async interceptForm(event: Event): Promise<void> {
        event.preventDefault();

        if (!this.form) {
            console.error('Form name is required for MFA validation.');
            return;
        }

        if (this.isLoginFlow && !(await this.isRequireAdditionalAuth())) {
            this.closePopup();
            return;
        }

        await this.initialRequest();
    }

    protected async isRequireAdditionalAuth(): Promise<boolean> {
        try {
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
            });

            const contentType = response.headers.get('content-type');

            if (contentType?.includes('application/json')) {
                const responseParams: AuthResponse = await response.json();

                return responseParams.requiresAdditionalAuth === true;
            }
        } catch (error) {
            console.error('Error checking additional auth requirement:', error);
        }

        return false;
    }

    protected async initialRequest(): Promise<void> {
        try {
            const emailInput = this.form.querySelector('input[type="email"]') as HTMLInputElement;
            const data = this.isLoginFlow ? JSON.stringify({ email: emailInput.value }) : null;
            const content = await this.ajaxProvider.fetch(data);

            this.popup.togglePopup(true);
            this.popupContentRerender(content);

            this.initPopup();
        } catch (error) {
            console.error('Error during initial MFA request:', error);
        }
    }

    protected initPopup(): void {
        this.setupDynamicContentEvents();

        this.boundInterceptForm = this.interceptForm.bind(this);
        this.form.addEventListener('submit', this.boundInterceptForm);

        const onPopupClosedBound = this.onPopupClosed.bind(this);

        this.popup.addEventListener(EVENT_POPUP_CLOSED, onPopupClosedBound, { signal: this.abortController.signal });
    }

    protected popupContentRerender(content: string): void {
        if (!this.isLoginFlow) {
            return;
        }

        requestAnimationFrame(() => {
            this.popup.replaceContent(content);
            this.setupDynamicContentEvents();
        });
    }

    protected onPopupClosed(): void {
        if (this.boundInterceptForm) {
            this.form.removeEventListener('submit', this.boundInterceptForm);
        }

        this.enableSingleClickButton();

        this.abortController.abort();

        this.abortController = new AbortController();
    }

    protected enableSingleClickButton(): void {
        const button = this.form.querySelector<HTMLButtonElement>(this.SINGLE_CLICK_BTN);

        if (button) {
            button.disabled = false;
        }
    }

    protected setupDynamicContentEvents(): void {
        const popupForm = this.popup.clone.querySelector('form');
        const contentElement = this.popup.clone.querySelector(this.CONTENT_SELECTOR);

        if (!popupForm || !contentElement) {
            console.error('Required elements not found in popup content');
            return;
        }

        const dynamicUrl = contentElement.getAttribute('url');
        this.ajaxProviderDynamic.setAttribute('url', dynamicUrl);
        popupForm.addEventListener('submit', this.interceptPopupForm.bind(this));
    }

    protected async interceptPopupForm(event: Event): Promise<void> {
        event.preventDefault();

        try {
            const popupForm = this.popup.clone.querySelector('form');
            if (!popupForm) return;

            const formData = new FormData(popupForm);
            const content = await this.ajaxProviderDynamic.fetch(formData);

            if (content.toString().includes(this.ERROR_ATTR)) {
                this.closePopupOnError();
                return;
            }

            if (content.toString().includes(this.CLOSE_POPUP_ATTR)) {
                this.closePopup();
                return;
            }

            this.popup.replaceContent(content);

            this.setupDynamicContentEvents();
        } catch (error) {
            console.error('Error processing popup form:', error);
        }
    }

    protected closePopup(): void {
        this.popup.dispatchEvent(new CustomEvent(EVENT_CLOSE_POPUP));
        this.form.submit();
    }

    protected closePopupOnError(): void {
        this.popup.dispatchEvent(new CustomEvent(EVENT_CLOSE_POPUP));
        location.reload();
    }

    protected get toggleClassName(): string {
        return this.getAttribute('toggle-class-name');
    }

    protected get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    protected get isLoginFlow(): boolean {
        return this.getAttribute('is-login-flow') === 'true';
    }

    protected get isJsEnabledHandler(): boolean {
        return this.getAttribute('is-js-enabled-handler') === 'true';
    }

    protected addJsEnabledValidationTokens(): void {
        const inputName = `${this.form.getAttribute('name')}${this.JS_MULTI_FACTOR_AUTHENTICATION_HANDLER_FIELD}`;
        let mfaInput = this.form.querySelector<HTMLInputElement>(`input[name="${inputName}"]`);

        if (!mfaInput) {
            mfaInput = document.createElement('input');
            mfaInput.type = 'hidden';
            mfaInput.name = inputName;
            mfaInput.value = 'true';

            this.form.appendChild(mfaInput);
        }
    }
}
