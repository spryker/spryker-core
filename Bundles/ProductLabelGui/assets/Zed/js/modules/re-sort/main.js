/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var reSortList = require('./re-sort-list');

$(document).ready(function() {
    reSortList.initialize('#js-re-sort-list', '#js-list-save-button');
});
