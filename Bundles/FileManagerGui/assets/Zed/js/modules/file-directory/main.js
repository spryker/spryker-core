/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var fileDirectoryTable = require('./file-directory-table');

$(document).ready(function() {
    fileDirectoryTable.initialize('#file-directory-table');
});
