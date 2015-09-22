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
    console.log(adjustmentFee);
    sum = sum + adjustmentFee;

    $('#form_amount').val(sum);
}

$(document).ready(function() {
    $('table.table').on('change', 'input', function(e) {
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
