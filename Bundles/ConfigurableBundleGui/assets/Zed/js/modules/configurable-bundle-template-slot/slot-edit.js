/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectTableAPI = require('../../../../../../ProductListGui/assets/Zed/js/modules/assign');

$(document).ready(function () {
    var availableProductsTable = new SelectTableAPI();
    var assignedProductsTable = new SelectTableAPI();

    availableProductsTable.init(
        '#availableProductConcreteTable',
        '#productsToBeAssigned',
        '.availableProductConcreteTable-all-products-checkbox',
        'a[href="#tab-content-assignment_product"]',
        '#productListAggregate_productIdsToBeAssigned'
    );

    assignedProductsTable.init(
        '#assignedProductConcreteTable',
        '#productsToBeDeassigned',
        '.assignedProductConcreteTable-all-products-checkbox',
        'a[href="#tab-content-deassignment_product"]',
        '#productListAggregate_productIdsToBeDeAssigned'
    );
});
