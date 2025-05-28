export default class FormWithExternalFields {
    form;
    formSelector;
    externalFieldsAttribute;
    externalFields = [];

    constructor(formSelector = '.js-form-has-external-data', externalFieldsAttribute = 'external-fields') {
        this.formSelector = formSelector;
        this.externalFieldsAttribute = externalFieldsAttribute;
        this.form = document.querySelector(this.formSelector);

        if (!this.form) {
            return;
        }

        this.externalFields = this.getExternalFields();
        this.mapEvents();
    }

    mapEvents() {
        this.form.addEventListener('submit', (event) => {
            this.onSubmit(event);
        });

        this.externalFields.forEach((field) => {
            field.removeAttribute('onchange');
            field.removeAttribute('onblur');
            field.addEventListener('change', (event) => {
                event.preventDefault();
                this.onSubmit(event);
            });
        });
    }

    onSubmit(event) {
        event.preventDefault();

        const url = this.buildPageUrl(
            this.form.getAttribute('action') ?? this.getCurrentUrlWithoutParams(),
            this.externalFields,
        );
        window.location.href = url;
    }

    buildPageUrl(baseUrl, includeFields) {
        const formData = new FormData(this.form);
        const urlParams = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value) {
                urlParams.append(key, value.toString());
            }
        }

        this.externalFields.forEach((field) => {
            const fieldName = field.getAttribute('name');
            const fieldValue = field.value;
            if (fieldValue) {
                urlParams.append(fieldName, fieldValue);
            }
        });

        const queryString = urlParams.toString();
        return queryString ? `${baseUrl}?${queryString}` : baseUrl;
    }

    getCurrentUrlWithoutParams() {
        return window.location.origin + window.location.pathname;
    }

    getExternalFields() {
        return this.externalFieldNames
            .map((fieldName) => {
                return document.querySelector(`[name="${fieldName}"]`);
            })
            .filter((element) => element !== null);
    }

    get externalFieldNames() {
        try {
            return JSON.parse(this.form.getAttribute(this.externalFieldsAttribute));
        } catch (error) {
            console.error('Failed to parse external field names:', error);
            return [];
        }
    }
}
