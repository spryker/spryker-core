/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectCountryTableAPI = require('../select-country-table-api/select-country-table-api');

$(document).ready(function () {
    var availableCountriesTable = new SelectCountryTableAPI();
    var assignedCountriesTable = new SelectCountryTableAPI();

    availableCountriesTable.init(
        '#available-country-table',
        '#countriesToBeAssigned',
        '.js-country-checkbox',
        'a[href="#tab-content-assignment_country"]',
        '#store_countryCodesToBeAssigned',
    );

    assignedCountriesTable.init(
        '#assigned-country-table',
        '#countriesToBeUnassigned',
        '.js-country-checkbox',
        'a[href="#tab-content-deassignment_country"]',
        '#store_countryCodesToBeDeAssigned',
    );
});

module.exports = SelectCountryTableAPI;
