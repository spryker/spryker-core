/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../../scss/main.scss');

var thresholdStrategyToggle = function(thresholdGroup) {
    var strategyKey = $('input[name="global-threshold[' + thresholdGroup + 'Threshold][strategy]"]:checked').val();

    $('.threshold-key-' + strategyKey).removeClass('hidden');
    $('.threshold_group_' + thresholdGroup + ':not(.threshold-key-'+ strategyKey +')').addClass('hidden');
};

$(document).ready(function () {
    thresholdStrategyToggle('hard');
    thresholdStrategyToggle('soft');

    $('input[name="global-threshold[hardThreshold][strategy]"]').click(function () {
        thresholdStrategyToggle('hard');
    });

    $('input[name="global-threshold[softThreshold][strategy]"]').click(function() {
        thresholdStrategyToggle('soft');
    });

    if ($('input[name="global-threshold[hardThreshold][strategy]"][value!=""]').length === 1) {
        $('input[name="global-threshold[hardThreshold][strategy]"]').parents('.form-group').addClass('hidden');
    }

    if ($('input[name="global-threshold[softThreshold][strategy]"][value!=""]').length === 1) {
        $('input[name="global-threshold[softThreshold][strategy]"]').parents('.form-group').addClass('hidden');
    }

    $('#global-threshold_storeCurrency').change(function() {
        window.location.href = '/sales-order-threshold-gui/global?store_currency='+$(this).val();
    })
});
