$(document).ready(function() {
    $('.gui-table-data').dataTable({
        bFilter: false
    });
    /**
     * all elements with the same class will have the same height
     */
    $('.fix-height').sprykerFixHeight();
});
