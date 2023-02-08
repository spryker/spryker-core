/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const AvailableWarehouseTable = require('./available-warehouse-table');
const AssignedWarehouseTable = require('./assigned-warehouse-table');

$(document).ready(() => {
    new AvailableWarehouseTable();
    new AssignedWarehouseTable();
});
