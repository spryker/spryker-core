/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    var $activeFrom = $('#price_product_schedule_activeFrom_date');
    var $activeTo = $('#price_product_schedule_activeTo_date');
    var $store = $('#price_product_schedule_priceProduct_moneyValue_store_idStore');
    var $currency = $('#price_product_schedule_priceProduct_moneyValue_currency_idCurrency');
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

    if (!$store.val()) {
        $('#active_from_timezone_text').hide();
        $('#active_to_timezone_text').hide();
    }

    $store.change(function() {
        var data = {};
        data.idStore = $store.val();
        $currency.find('option:gt(0)').remove();
        $.ajax({
            url: '/currency/currencies-for-store',
            type: 'POST',
            data: data,
            success: function(data) {
                $.each(data.currencies, function(key, currency) {
                    $currency.append($('<option value="'+ currency.id_currency +'">'+ currency.code +'</option>'));
                });
                $('.timezone').html(data.store.timezone);
                $('#active_from_timezone_text').show();
                $('#active_to_timezone_text').show();
            }
        });
    });
});
