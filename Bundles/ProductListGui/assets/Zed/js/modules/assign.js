/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectTableAPI = require('./select-table-api');

$(document).ready(function () {
    var availableProductsTable = new SelectTableAPI();
    var assignedProductsTable = new SelectTableAPI();

    availableProductsTable.init(
        '#available-product-concrete-table',
        '#productsToBeAssigned',
        '.available-product-concrete-table-all-products-checkbox',
        'a[href="#tab-content-assignment_product"]',
        '#productListAggregate_productIdsToBeAssigned'
    );

    assignedProductsTable.init(
        '#assigned-product-concrete-table',
        '#productsToBeDeassigned',
        '.assigned-product-concrete-table-all-products-checkbox',
        'a[href="#tab-content-deassignment_product"]',
        '#productListAggregate_productIdsToBeDeAssigned'
    );
});

module.exports = SelectTableAPI;
