export class CopyAction {
    constructor(selector = '.copy-button') {
        this.selector = selector;
        this.init();
    }

    init() {
        const copyButtons = document.querySelectorAll(this.selector);
        copyButtons.forEach((button) => {
            button.addEventListener('click', this.handleCopyAction.bind(this));
        });
    }

    handleCopyAction(event) {
        event.preventDefault();
        const button = event.currentTarget;
        const targetId = button.getAttribute('data-copy-target');

        if (!targetId) {
            console.error('No data-copy-target attribute found on the button');
            return;
        }

        const targetElement = document.getElementById(targetId);

        if (!targetElement) {
            console.error(`Target element with id "${targetId}" not found`);
            return;
        }

        const textToCopy = targetElement.textContent || targetElement.value || '';
        this.copyToClipboard(textToCopy, button);
    }

    async copyToClipboard(text, button) {
        if (navigator.clipboard && window.isSecureContext) {
            try {
                await navigator.clipboard.writeText(text);
                this.showFeedback(button, this.getSuccessText(button));
            } catch (error) {
                console.error(this.getFailedText(button), error);
                this.fallbackCopyToClipboard(text, button);
            }
        } else {
            this.fallbackCopyToClipboard(text, button);
        }
    }

    fallbackCopyToClipboard(text, button) {
        try {
            const textArea = document.createElement('textarea');
            textArea.value = text;

            textArea.style.position = 'fixed';
            textArea.style.left = '-99999px';
            textArea.style.top = '-99999px';

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            const successful = document.execCommand('copy');

            document.body.removeChild(textArea);

            if (successful) {
                this.showFeedback(button, this.getSuccessText(button));
            } else {
                this.showFeedback(button, this.getFailedText(button));
            }
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
