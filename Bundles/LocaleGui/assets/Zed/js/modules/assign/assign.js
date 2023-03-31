/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectLocaleTableAPI = require('../select-locale-table-api/select-locale-table-api');

$(document).ready(function () {
    var availableLocalesTable = new SelectLocaleTableAPI();
    var assignedLocalesTable = new SelectLocaleTableAPI();

    availableLocalesTable.init(
        '#available-locale-table',
        '#localesToBeAssigned',
        '.js-locale-checkbox',
        'a[href="#tab-content-assignment_locale"]',
        '#store_localeCodesToBeAssigned',
    );

    assignedLocalesTable.init(
        '#assigned-locale-table',
        '#localesToBeUnassigned',
        '.js-locale-checkbox',
        'a[href="#tab-content-deassignment_locale"]',
        '#store_localeCodesToBeDeAssigned',
    );
});

module.exports = SelectLocaleTableAPI;
