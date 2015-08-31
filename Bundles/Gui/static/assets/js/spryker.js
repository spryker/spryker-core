$(document).ready(function() {
    /**
     * Spryker Ajax Calls Container
     * @type {SprykerAjax}
     */
    var spyAj = new SprykerAjax();

    /** Draw data tables */
    $('.gui-table-data').dataTable();

    /** all elements with the same class will have the same height */
    $('.fix-height').sprykerFixHeight();

    $('.spryker-form-autocomplete').each(function (key, value) {
        var obj = $(value);
        obj.autocomplete({
            source: obj.data('url'),
            minLength: 3
        });
    });

    /** trigger change status active|inactive with an ajax call when click on checkbox */
    $('.gui-table-data').on('click', '.active-checkbox', function(){
        var elementId = $(this).attr('id').replace('active-', '');
        spyAj.setUrl('/discount/voucher/status').changeActiveStatus(elementId);
    });

    $('.dropdown-toggle').dropdown();
});
