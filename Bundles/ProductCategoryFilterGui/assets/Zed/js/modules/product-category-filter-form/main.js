/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var filters = require('./filters');

$(document).ready(function() {
    $('#addButton').on('click', function() {
        var currentList = JSON.parse(filters.getCurrentList());
        var filter = $('#product_category_filter').val();
        if($.inArray(filter, currentList) !== -1) {
            alert('Filter "'+ filter +'" already defined');
        } else {
            filters.addToList(filter);
        }
    });
});
