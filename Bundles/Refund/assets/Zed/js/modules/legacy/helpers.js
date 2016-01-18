/**
 * 
 * Refund helpers
 * @copyright: Spryker Systems GmbH
 *
 */

'use strict';

module.exports = {
    calculateTotalRefundAmount: function() {
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

        if (sum < 0) {
            adjustmentFee = adjustmentFee + sum;
            $('#form_adjustment_fee').val(adjustmentFee);
            sum = 0;
        }

        var maxSum = parseInt($('div.refund-form').data('max'), 10);
        if (sum > maxSum) {
            adjustmentFee = adjustmentFee - (sum - maxSum);
            $('#form_adjustment_fee').val(adjustmentFee);
            sum = maxSum;
        }

        $('#form_amount').val(sum);
    }
};
