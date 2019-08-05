/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var templateListTable = require('./template-list-table');

$(document).ready(function() {
    templateListTable.initialize('#template-list-table');
});
