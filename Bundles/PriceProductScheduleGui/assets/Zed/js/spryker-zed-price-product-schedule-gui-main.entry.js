/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var PriceProductScheduleCreate = require('./modules/price-product-schedule-create');
require('./modules/scheduled-prices-errors-form');
require('../sass/main.scss');

$(document).ready(function() {
    new PriceProductScheduleCreate({
        $activeFrom: $('#price_product_schedule_activeFrom_date'),
        $activeTo: $('#price_product_schedule_activeTo_date'),
        $store: $('#price_product_schedule_priceProduct_moneyValue_store_idStore'),
        $currency: $('#price_product_schedule_priceProduct_moneyValue_currency_idCurrency'),
        $activeFromTimezoneText: $('#active_to_timezone_text'),
        $activeToTimezoneText: $('#active_from_timezone_text'),
        $timezone: $('.timezone'),
        requestUrl: '/currency/currencies-for-store',
        dataKey: 'idStore',
        currencies: {
            response: 'currencies',
            value: 'id_currency',
            text: 'code'
        },
        submit: $('#price_product_schedule_submit'),
        form: $('#price_product_schedule_form'),
    });
});
