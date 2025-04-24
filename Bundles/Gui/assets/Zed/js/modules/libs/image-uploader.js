export class ImageUploader {
    static #defaultOptions = ImageUploader.#createDefaultOptions();

    static #createDefaultOptions() {
        const base = '.js-image-uploader';
        return {
            baseSelector: base,
            inputSelector: `${base}__input`,
            imageSelector: `${base}__image`,
            deleteInputSelector: `${base}__delete-input`,
            deleteButtonSelector: `${base}__delete`,
            loadingClass: 'is-loading',
            hiddenClass: 'hidden',
            placeholderAttribute: 'placeholder',
            initialImageAttribute: 'initial-image',
            idAttribute: 'data-id',
        };
    }

    constructor(options = {}) {
        this.options = { ...ImageUploader.#defaultOptions, ...options };
        this.init();
    }

    init() {
        const uploaders = document.querySelectorAll(this.options.baseSelector) ?? [];

        for (const uploader of uploaders) {
            const data = {
                uploader: uploader,
                input: uploader.querySelector(this.options.inputSelector),
                image: uploader.querySelector(this.options.imageSelector),
                deleteInput: uploader.querySelector(this.options.deleteInputSelector),
                deleteButton: uploader.querySelector(this.options.deleteButtonSelector),
                reader: new FileReader(),
            };

            if (!data.input || !data.image) {
                continue;
            }

            data.input.addEventListener('change', (event) => this.onChange(event, data));
            document.addEventListener('click', (event) => this.onDelete(event, data));
            data.reader.addEventListener('load', (event) => this.onLoad(event, data));
        }
    }

    onChange(event, data) {
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        data.uploader.classList.add(this.options.loadingClass);
        data.reader.readAsDataURL(file);
    }

    onLoad(event, data) {
        data.image.src = event.target.result;
        data.deleteInput.removeAttribute('checked');
        data.uploader.classList.remove(this.options.loadingClass);
        data.deleteButton.classList.remove(this.options.hiddenClass);
    }

    onDelete(event, data) {
        const id = data.uploader.getAttribute(this.options.idAttribute);
        const button = event.target.closest(`${this.options.deleteButtonSelector}[data-id="${id}"]`);

        if (!button) {
            return;
        }

        data.image.src = data.uploader.getAttribute(this.options.placeholderAttribute);
        data.input.value = '';
        data.deleteButton.classList.add(this.options.hiddenClass);
        button.blur();

        if (!data.uploader.getAttribute(this.options.initialImageAttribute)) {
            return;
        }

        data.deleteInput.setAttribute('checked', 'checked');
        this.detachModalBehaviorFromDeleteButton();
    }

    detachModalBehaviorFromDeleteButton() {
        const trigger = data.uploader.querySelector('[data-toggle="modal"]');

        if (!trigger) {
            return;
        }

        trigger.removeAttribute('data-toggle');
        trigger.removeAttribute('data-target');
        trigger.classList.add(this.options.hiddenClass);
        trigger.classList.add(this.options.deleteButtonSelector.slice(1));

        const clone = trigger.cloneNode(true);
        trigger.replaceWith(clone);
        data.deleteButton = clone;
    }
}
