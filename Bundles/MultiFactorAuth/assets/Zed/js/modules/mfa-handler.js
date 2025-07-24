export class MfaHandler {
    static #defaultOptions = MfaHandler.#createDefaultOptions();

    static #createDefaultOptions() {
        const base = '.js-mfa-handler';
        return {
            baseSelector: base,
            urlAttribute: `data-url`,
            formSelectorAttribute: `data-form-selector`,
            modalIdAttribute: 'data-modal-id',
            isLogin: 'data-is-login',
            submitSelector: '.safe-submit',
        };
    }

    constructor(options = {}) {
        this.options = { ...MfaHandler.#defaultOptions, ...options };
        this.init();
    }

    init() {
        const mfaHandlers = document.querySelectorAll(this.options.baseSelector) ?? [];

        for (const mfaHandler of mfaHandlers) {
            const data = {
                mfaHandler: mfaHandler,
                url: mfaHandler.getAttribute(this.options.urlAttribute),
                form: document.querySelector(mfaHandler.getAttribute(this.options.formSelectorAttribute)),
                modal: document.getElementById(mfaHandler.getAttribute(this.options.modalIdAttribute)),
                isLogin: mfaHandler.getAttribute(this.options.isLogin) === 'true',
            };

            if (!data.url || !data.form) {
                continue;
            }

            $(data.modal).appendTo('body');

            this.renderJsValidationToken(data.form);

            $(data.modal).on('hidden.bs.modal', () => this.onModalHide(data));

            data.form.addEventListener('submit', async (event) => await this.onSubmit(event, data));
        }
    }

    async onSubmit(event, data) {
        event.preventDefault();

        if (data.isLogin) {
            const responseData = await this.handleAdditionalAuth(data);

            if (responseData.failedLogin === true) {
                data.form.submit();

                return;
            }

            if (responseData.requiresAdditionalAuth === true) {
                await this.handleResponse(data);

                return;
            }

            location.reload();

            return;
        }

        await this.handleResponse(data);
    }

    async handleAdditionalAuth(data) {
        try {
            const formData = new FormData(data.form);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', data.form.action, false);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);

            const result = {
                requiresAdditionalAuth: false,
                failedLogin: false,
            };

            const contentType = xhr.getResponseHeader('Content-Type');

            if (contentType?.includes('application/json')) {
                const data = JSON.parse(xhr.responseText);
                result.requiresAdditionalAuth = data.requires_additional_auth;

                return result;
            }

            if (xhr.responseURL.includes('/security-gui/login')) {
                result.failedLogin = true;

                return result;
            }

            return result;
        } catch (error) {
            console.error('Server error:', error);
            this.closePopupOnError();
        }

        return false;
    }

    async handleResponse(data) {
        try {
            const url = new URL(data.url, window.location.origin).toString();
            const formData = new FormData();

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
            });

            const html = await response.text();
            this.onSuccess(html, data);
        } catch (error) {
            console.error('Error:', error);
            this.closePopupOnError();
        }
    }

    onModalHide(data) {
        const submitButton = $(data.form.querySelector(this.options.submitSelector));

        submitButton.prop('disabled', false).removeClass('disabled');
    }

    onSuccess(html, data) {
        const modal = $(data.modal);
        modal.find('.modal-body').html(html);
        modal.modal('show');

        this.attachModalFormListener(modal, data);
    }

    attachModalFormListener(modal, data) {
        modal.find('form').on('submit', (event) => this.onModalResponse(event, modal, data));
    }

    async onModalResponse(event, modal, data) {
        event.preventDefault();

        try {
            const actionUrl = modal.find('.js-mfa-data').data('url');
            const formData = new FormData(event.currentTarget);

            const response = await fetch(new URL(actionUrl, window.location.origin).toString(), {
                method: 'POST',
                body: formData,
            });

            const html = await response.text();
            const tempContainer = $('<div>').html(html);

            if (tempContainer.find('[data\\-close\\-popup]').length > 0) {
                this.closePopupOnSuccess(modal, data);
            }

            modal.find('.modal-body').html(html);
            this.attachModalFormListener(modal, data);
        } catch (error) {
            console.error('Error:', error);
            this.closePopupOnError(data);
        }
    }

    closePopupOnSuccess(modal, data) {
        modal.modal('hide');

        if (data.isLogin) {
            location.reload();
            return;
        }

        data.form.submit();
    }

    closePopupOnError(data) {
        const modal = $(data.modal);

        modal.modal('hide');
    }

    renderJsValidationToken(form) {
        const inputName = 'multi_factor_auth_enabled';
        let mfaInput = form.querySelector(`input[name="${inputName}"]`);

        if (!mfaInput) {
            mfaInput = document.createElement('input');
            mfaInput.type = 'hidden';
            mfaInput.name = inputName;
            mfaInput.value = 'true';

            form.appendChild(mfaInput);
        }
    }
}
