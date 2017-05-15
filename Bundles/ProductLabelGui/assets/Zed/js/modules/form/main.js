/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var datePicker = require('./date-picker');
var relationTable = require('./abstract-product-relation-table');

$(document).ready(function() {
    datePicker.initialize('.js-valid-from-date-picker', '.js-valid-to-date-picker');
    relationTable.initialize(
        '#related-products-table',
        '.js-abstract-product-checkbox',
        '#js-abstract-product-ids-csv-field'
    );
});
