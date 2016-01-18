/**
 * 
 * Refund logic
 * @copyright: Spryker Systems GmbH
 *
 */

'use strict';

require('./helpers.js');

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
        //TODO: Maybe add a warning when not all expenses are refunded but if all items will be refunded? CD-448

        calculateTotalRefundAmount();
    });

    calculateTotalRefundAmount();
});

