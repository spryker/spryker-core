var clickedButtonUrl = null;

$(document).ready(function () {
    $('#used-by-table').on('click', 'td.actions a.btn', function (e) {
        e.preventDefault();

        clickedButtonUrl = $(this).attr('href');

        if (window.spryker?.isBootstrapVersionLatest) {
            var bootstrap = window.spryker.bootstrap;
            var confirmModal = new bootstrap.Modal($('#confirmation-modal-window'));
            confirmModal.show();
        } else {
            $('#confirmation-modal-window').modal('show');
        }
    });

    $('.js-btn-confirm').on('click', function () {
        window.location.href = clickedButtonUrl;
    });
});
