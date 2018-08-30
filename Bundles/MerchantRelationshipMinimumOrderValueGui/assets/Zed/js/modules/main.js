/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../scss/main.scss');

var softThresholdStrategyToggle = function() {
    var softStrategy = $('input[name="threshold[softStrategy]"]:checked').val();
    var softValueBlock = $('#threshold_softThreshold').parent();
    var softFixedFeeBlock = $('#threshold_softFixedFee').parent();
    var softFlexibleFeeBlock = $('#threshold_softFlexibleFee').parent();

    if (softStrategy == 'soft-threshold') {
        softValueBlock.removeClass('hidden');
        softFixedFeeBlock.addClass('hidden');
        softFlexibleFeeBlock.addClass('hidden');
    } else if (softStrategy == 'soft-threshold-fixed-fee') {
        softValueBlock.removeClass('hidden');
        softFixedFeeBlock.removeClass('hidden');
        softFlexibleFeeBlock.addClass('hidden');
    } else if (softStrategy == 'soft-threshold-flexible-fee') {
        softValueBlock.removeClass('hidden');
        softFixedFeeBlock.addClass('hidden');
        softFlexibleFeeBlock.removeClass('hidden');
    }
};

$(document).ready(function () {
    softThresholdStrategyToggle();
    $('input[name="threshold[softStrategy]"]').click(softThresholdStrategyToggle);

    $('#threshold_storeCurrency').change(function() {
        var idMerchantRelationship = $('#threshold_storeCurrency').val();
        window.location.href = '/merchant-relationship-minimum-order-value-gui/edit?id-merchant-relationship=' + idMerchantRelationship
            +'&store_currency='+$(this).val();
    })
});
