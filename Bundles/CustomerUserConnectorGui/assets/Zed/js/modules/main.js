/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var availableProductTable = require('./available-product-table');
var assignedProductTable = require('./assigned-product-table');

$(document).ready(function() {
    availableProductTable.initialize();
    assignedProductTable.initialize();
});
