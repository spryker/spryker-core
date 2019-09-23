var clickedButtonUrl = null;

$(document).ready(function () {
    $('#used-by-table').on('click', 'td.actions a.btn', function (e) {
        e.preventDefault();

        clickedButtonUrl = $(this).attr('href');
        $('#delete-confirmation-modal-window').modal('show');
    });

    $('#delete-confirmation-modal-window').on('click', '#btn-confirm', function (e) {
        e.preventDefault();

        window.location.href = clickedButtonUrl;
    });
});
