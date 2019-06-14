/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var availableCustomerTable = require('./available-customer-table');
var assignedCustomerTable = require('./assigned-customer-table');

$(document).ready(function() {
    availableCustomerTable.initialize();
    assignedCustomerTable.initialize();
});
