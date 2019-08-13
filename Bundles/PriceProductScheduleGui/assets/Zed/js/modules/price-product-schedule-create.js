/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var DependentSelectBox = require('ZedGuiModules/libs/dependent-select-box');

$(document).ready(function() {
    var $activeFrom = $('#price_product_schedule_activeFrom_date');
    var $activeTo = $('#price_product_schedule_activeTo_date');
    var $store = $('#price_product_schedule_priceProduct_moneyValue_store_idStore');
    var $currency = $('#price_product_schedule_priceProduct_moneyValue_currency_idCurrency');
    var $activeFromTimezoneText = $('#active_to_timezone_text');
    var $activeToTimezoneText = $('#active_from_timezone_text');
    var $timezone = $('.timezone');
    var currencies = {
        response: 'currencies',
        value: 'id_currency',
        text: 'code'
    };

    if (!$store.val()) {
        $activeFromTimezoneText.hide();
        $activeToTimezoneText.hide();
    }

    $activeFrom.datepicker({
        altFormat: "yy-mm-dd",
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        defaultData: 0,
    });

    $activeTo.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        defaultData: 0,
    });

    new DependentSelectBox(
        $store,
        $currency,
        '/currency/currencies-for-store',
        'idStore',
        currencies,
        successCallback
    );

    function successCallback(data) {
        if (!data.store) {
            $activeFromTimezoneText.hide();
            $activeToTimezoneText.hide();

            return;
        }

        $timezone.each(function (index, value) {
            $(value).text(data.store.timezone);
        });

        $activeFromTimezoneText.show();
        $activeToTimezoneText.show();
    }
});
