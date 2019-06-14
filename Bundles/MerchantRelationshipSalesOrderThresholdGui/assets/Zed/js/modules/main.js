/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../../scss/main.scss');

var thresholdStrategyToggle = function(thresholdGroup) {
    var strategyKey = $('input[name="merchant-relationship-threshold[' + thresholdGroup + 'Threshold][strategy]"]:checked').val();

    $('.threshold-key-' + strategyKey).removeClass('hidden');
    $('.threshold_group_' + thresholdGroup + ':not(.threshold-key-'+ strategyKey +')').addClass('hidden');
};

$(document).ready(function () {
    thresholdStrategyToggle('hard');
    thresholdStrategyToggle('soft');

    $('input[name="merchant-relationship-threshold[hardThreshold][strategy]"]').click(function () {
        thresholdStrategyToggle('hard');
    });

    $('input[name="merchant-relationship-threshold[softThreshold][strategy]"]').click(function() {
        thresholdStrategyToggle('soft');
    });

    if ($('input[name="merchant-relationship-threshold[hardThreshold][strategy]"][value!=""]').length === 1) {
        $('input[name="merchant-relationship-threshold[hardThreshold][strategy]"]').parents('.form-group').addClass('hidden');
    }

    if ($('input[name="merchant-relationship-threshold[softThreshold][strategy]"][value!=""]').length === 1) {
        $('input[name="merchant-relationship-threshold[softThreshold][strategy]"]').parents('.form-group').addClass('hidden');
    }

    $('#merchant-relationship-threshold_storeCurrency').change(function() {
        var idMerchantRelationship = $('#merchant-relationship-threshold_idMerchantRelationship').val();
        window.location.href = '/merchant-relationship-sales-order-threshold-gui/edit?id-merchant-relationship=' + idMerchantRelationship
            + '&store_currency='+$(this).val();
    })
});
