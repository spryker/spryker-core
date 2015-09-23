'use strict';

function calculateTotalRefundAmount() {
    var sum = 0;
    $('table.table input').each(function() {
        var quantity = parseInt($(this).val(), 10);
        if (!quantity) {
            return;
        }
        var price = $(this).data('price');
        sum = sum + quantity * price;
    });

    var adjustmentFee = parseInt($('#form_adjustment_fee').val(), 10) || 0;
    sum = sum + adjustmentFee;

    // @todo CD-462 make this run only in refund pages. This is global scope and affects all pages
    //$('#form_amount').val(sum);
}

$(document).ready(function() {
    $('table.add-refund').on('change', 'input', function(e) {
        var quantity = parseInt($(this).val(), 10);
        if (quantity < 0) {
            quantity = 0;
            $(this).val(quantity);
        }

        var max = $(this).data('quantity');
        if (quantity > max) {
            quantity = max;
            $(this).val(quantity);
        }

        calculateTotalRefundAmount();
    });

    $('#form_adjustment_fee').change(function(e) {
        //TODO: Maybe calculate max possible fee and if necessary to return all expenses? CD-448

        calculateTotalRefundAmount();
    });

    calculateTotalRefundAmount();
});
