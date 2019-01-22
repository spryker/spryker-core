/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $ = require('jquery');

$(document).ready(function() {
    $('#paymentForm_ratepayInstallment_debit_pay_type').on('change', function(event) {
        var sectionPattern = '.ratepay-installment-pay-type-' + $(event.target).val().toLowerCase();
        $('.ratepay-installment-pay-type').hide();
        $(sectionPattern).show();
    }).trigger('change');

    $('#paymentForm_ratepayInstallment_installment_calculation_type').on('change', function(event) {
        var sectionPattern = '.ratepay-installment-' + $(event.target).val().toLowerCase();
        $('.ratepay-installment-calculation-type').hide();
        $(sectionPattern).show();
    }).trigger('change');
});
