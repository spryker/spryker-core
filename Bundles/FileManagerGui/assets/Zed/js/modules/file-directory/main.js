/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var fileDirectoryTree = require('./file-directory-tree');

$(document).ready(function() {
    fileDirectoryTree.initialize();
});
