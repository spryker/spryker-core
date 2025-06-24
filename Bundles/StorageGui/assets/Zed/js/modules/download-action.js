export class DownloadAction {
    constructor(selector = '.download-button') {
        this.selector = selector;
        this.init();
    }

    init() {
        const downloadButtons = document.querySelectorAll(this.selector);
        downloadButtons.forEach((button) => {
            button.addEventListener('click', this.handleDownloadAction.bind(this));
        });
    }

    handleDownloadAction(event) {
        event.preventDefault();
        const button = event.currentTarget;
        const targetId = button.getAttribute('data-download-target');

        if (!targetId) {
            console.error('No data-download-target attribute found on the button');
            return;
        }

        const targetElement = document.getElementById(targetId);

        if (!targetElement) {
            console.error(`Target element with id "${targetId}" not found`);
            return;
        }

        const content = targetElement.textContent || targetElement.value || '';
        const filename = button.getAttribute('data-filename') || 'data.json';
        this.downloadAsFile(content, filename, button);
    }

    downloadAsFile(content, filename, button) {
        try {
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const downloadLink = document.createElement('a');
            downloadLink.href = url;
            downloadLink.download = filename;

            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            URL.revokeObjectURL(url);

            this.showFeedback(button, this.getSuccessText(button));
        } catch (err) {
            console.error(this.getFailedText(button), err);
            this.showFeedback(button, this.getFailedText(button));
        }
    }

    showFeedback(button, message) {
        const originalText = button.innerHTML;

        button.innerHTML = message;

        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    }

    getSuccessText(button) {
        return button.getAttribute('data-success-feedback-text');
    }

    getFailedText(button) {
        return button.getAttribute('data-failed-feedback-text');
    }
}
