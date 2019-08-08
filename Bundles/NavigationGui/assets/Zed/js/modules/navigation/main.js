/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var navigationTable = require('./navigation-table');

$(document).ready(function() {
    navigationTable.initialize('#navigation-table');
});
