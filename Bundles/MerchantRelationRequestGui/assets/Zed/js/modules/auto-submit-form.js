const autoSubmitForm = () => {
    const form = document.querySelector('form.js-auto-submit-form');

    form.querySelectorAll('select').forEach((select) => {
        select.addEventListener('change', () => {
            form.submit();
        });
    });
};

$(document).ready(function () {
    autoSubmitForm();
});
