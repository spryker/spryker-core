export class Dropzone {
    selector = null;

    constructor(selector = '.js-input-dropzone') {
        this.selector = selector;
        this.init();
    }

    init() {
        const dropzones = document.querySelectorAll(this.selector) ?? [];

        for (let i = 0; i < dropzones.length; i++) {
            const dropzone = dropzones[i];
            const data = {
                dropzone: dropzone,
                transfer: new DataTransfer(),
                input: dropzone.querySelector(`${this.selector}__input`),
                area: dropzone.querySelector(`${this.selector}__area`),
                fileTemplate: dropzone.querySelector(`[data-id="${dropzone.dataset.template}"]`),
                filesContainer: dropzone.querySelector(`${this.selector}__files`),
            };

            if (!data.input || !data.area) {
                continue;
            }

            data.input.addEventListener('change', (event) => this.onChange(event, data));
            data.area.addEventListener('drop', (event) => this.onDrag(event, data));
        }
    }

    onChange(event, data) {
        this.cancelEvent(event);
        this.setFiles(event.target.files, data);
    }

    onDrag(event, data) {
        this.cancelEvent(event);
        this.setFiles(event.dataTransfer.files, data);
    }

    cancelEvent(event) {
        event.preventDefault();
        event.stopPropagation();
    }

    setFiles(files, data) {
        const max = Number(data.area.dataset.max);
        const accept = data.input.getAttribute('accept');

        for (const file of Array.from(files)) {
            if (data.transfer.files.length >= max) {
                // eslint-disable-next-line no-console
                console.warn(`The maximum number of files is ${this.max}`);
                break;
            }

            const isFileTypeAllowed = file.type.split('/').some((type) => accept.includes(type));

            if (!isFileTypeAllowed || !file.type) {
                // eslint-disable-next-line no-console
                console.warn(`The file ${file.name} has an unsupported format`);
                continue;
            }

            data.transfer.items.add(file);
            this.addFileElement(file, data, data.transfer.files.length - 1);
        }

        data.input.files = data.transfer.files;
    }

    addFileElement(file, data, index) {
        if (!data.fileTemplate) {
            return;
        }

        const clone = data.fileTemplate.content.cloneNode(true);
        const fileElement = clone.querySelector(`${this.selector}__file`);
        const bytes = 1024;
        const decimal = 2;
        const size = (file.size / (bytes * bytes)).toFixed(decimal);

        clone.querySelector(`${this.selector}__file-name`).textContent = file.name;
        clone.querySelector(`${this.selector}__file-size`).textContent = size === '0.00' ? '< 0.01' : size;
        fileElement.addEventListener('click', () => this.deleteFile(data, fileElement, index));
        data.filesContainer.appendChild(clone);
    }

    deleteFile(data, element, index) {
        data.transfer.items.remove(index);
        data.input.files = data.transfer.files;
        element.remove();
    }
}
