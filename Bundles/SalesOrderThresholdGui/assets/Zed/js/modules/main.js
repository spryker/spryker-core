/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../scss/main.scss');

var softThresholdStrategyToggle = function() {
    var softStrategy = $('input[name="global-threshold[softStrategy]"]:checked').val();
    var softValueBlock = $('#global-threshold_softThreshold').parent();
    var softFixedFeeBlock = $('#global-threshold_softFixedFee').parent();
    var softFlexibleFeeBlock = $('#global-threshold_softFlexibleFee').parent();

    if (softStrategy == 'soft-minimum-threshold') {
        softValueBlock.removeClass('hidden');
        softFixedFeeBlock.addClass('hidden');
        softFlexibleFeeBlock.addClass('hidden');
    } else if (softStrategy == 'soft-minimum-threshold-fixed-fee') {
        softValueBlock.removeClass('hidden');
        softFixedFeeBlock.removeClass('hidden');
        softFlexibleFeeBlock.addClass('hidden');
    } else if (softStrategy == 'soft-minimum-threshold-flexible-fee') {
        softValueBlock.removeClass('hidden');
        softFixedFeeBlock.addClass('hidden');
        softFlexibleFeeBlock.removeClass('hidden');
    }
};

$(document).ready(function () {
    softThresholdStrategyToggle();
    $('input[name="global-threshold[softStrategy]"]').click(softThresholdStrategyToggle);

    $('#global-threshold_storeCurrency').change(function() {
        window.location.href = '/sales-order-threshold-gui/global?store_currency='+$(this).val();
    })
});
