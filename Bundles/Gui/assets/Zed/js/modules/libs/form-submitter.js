export class FormSubmitter {
    selector = null;
    debounceTimeout = null;
    debounceDelay = 500;

    constructor(selector = '.js-form-submitter') {
        this.selector = selector;
        this.init();
    }

    init() {
        for (let element of document.querySelectorAll(`${this.selector}`) ?? []) {
            const customEvent = element.getAttribute('data-submitter-event');
            const event = customEvent ? customEvent : element.tagName === 'BUTTON' ? 'click' : 'change';

            element.addEventListener(event, this.submitForm.bind(this));
        }
    }

    debounce(fn) {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => fn(), this.debounceDelay);
    }

    submitForm(event) {
        const formName = event.currentTarget.getAttribute('data-submitter-form-name');
        const form = formName
            ? document.querySelector(`form[name="${formName}"]`)
            : event.currentTarget.closest('form');

        if (form) {
            this.debounce(() => form.submit());
        }
    }
}
