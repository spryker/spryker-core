import Component from 'ShopUi/models/component';

export default class ImageUploader extends Component {
    protected image: HTMLImageElement;
    protected input: HTMLInputElement;
    protected deleteButton: HTMLButtonElement;
    protected deleteInput: HTMLInputElement;
    protected reader = new FileReader();

    protected readyCallback(): void {}
    protected init(): void {
        this.image = this.querySelector(`.${this.jsName}__image`);
        this.input = this.querySelector(`.${this.jsName}__input`);
        this.deleteInput = this.querySelector(`.${this.jsName}__delete-input`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.querySelector(`.${this.jsName}__input`)?.addEventListener('change', this.onChange.bind(this));
        document.addEventListener('click', this.onDelete.bind(this));
        this.reader.addEventListener('load', this.onLoad.bind(this));
    }

    protected onChange(event: Event) {
        const file = (event.target as HTMLInputElement).files[0];

        if (!file) {
            return;
        }

        this.classList.add(this.loadingClass);
        this.reader.readAsDataURL(file);
    }

    protected async onLoad(event: ProgressEvent<FileReader>) {
        this.image.src = event.target.result as string;
        this.deleteInput.removeAttribute('checked');
        this.classList.remove(this.loadingClass);
    }

    protected onDelete(event: Event) {
        const button = (event.target as HTMLElement).closest<HTMLButtonElement>(`.${this.jsName}__delete`);
        if (!button) {
            return;
        }

        this.image.src = this.placeholder;
        this.input.value = '';
        button.blur();

        if (!this.hasImage) {
            return;
        }

        this.deleteInput.setAttribute('checked', 'checked');
        const trigger = this.querySelector(`.${this.confirmationClass}`);

        if (trigger) {
            trigger.classList.remove(this.confirmationClass);
            trigger.classList.add(`${this.jsName}__delete`);

            const clone = trigger.cloneNode(true);
            trigger.replaceWith(clone);
        }
    }

    protected get hasImage(): boolean {
        return Boolean(this.getAttribute('initial-image'));
    }

    protected get placeholder(): string {
        return this.getAttribute('placeholder');
    }

    protected get loadingClass(): string {
        return this.getAttribute('loading-class');
    }

    protected get confirmationClass(): string {
        return this.getAttribute('confirmation-trigger');
    }
}
