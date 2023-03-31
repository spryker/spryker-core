/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectCurrencyTableAPI = require('../select-currency-table-api/select-currency-table-api');

$(document).ready(function () {
    var availableCurrenciesTable = new SelectCurrencyTableAPI();
    var assignedCurrenciesTable = new SelectCurrencyTableAPI();

    availableCurrenciesTable.init(
        '#available-currency-table',
        '#currenciesToBeAssigned',
        '.js-currency-checkbox',
        'a[href="#tab-content-assignment_currency"]',
        '#store_currencyCodesToBeAssigned',
    );

    assignedCurrenciesTable.init(
        '#assigned-currency-table',
        '#currenciesToBeUnassigned',
        '.js-currency-checkbox',
        'a[href="#tab-content-deassignment_currency"]',
        '#store_currencyCodesToBeDeAssigned',
    );
});

module.exports = SelectCurrencyTableAPI;
