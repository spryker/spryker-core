$(document).ready(function() {
    $('.gui-table-data').dataTable();
    /**
     * all elements with the same class will have the same height
     */
    $('.fix-height').sprykerFixHeight();

    $('.spryker-form-autocomplete').each(function (key, value) {
        var obj = $(value);
        obj.autocomplete({
            source: obj.data('url'),
            minLength: 3
        });
    });
});
