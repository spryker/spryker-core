var clickedButtonUrl = null;

$(document).ready(function () {
    $('#used-by-table').on('click', 'td.actions a.btn', function (e) {
        e.preventDefault();

        clickedButtonUrl = $(this).attr('href');
        $('#confirmation-modal-window').modal('show');
    });

    $('#btn-confirm').on('click', function () {
        window.location.href = clickedButtonUrl;
    });
});
