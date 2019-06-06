/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var categoryTable = require('./category-table');

$(document).ready(function() {
    categoryTable.initialize('#root-node-table');
});
