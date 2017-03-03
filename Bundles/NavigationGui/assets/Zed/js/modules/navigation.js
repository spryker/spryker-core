/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var navigationTree = require('./tree/navigation-tree');

// TODO: Clean up JS code

$(document).ready(function() {
    var navigationTable = $('#navigation-table').DataTable();

    $('#navigation-table tbody').on('click', 'tr', function(e) {
        if (!$(e.target).is('td')) {
            return;
        }

        navigationTable.rows().deselect();
        navigationTable.row($(this).index()).select();
    });

    navigationTable.on('draw', function() {
        navigationTable.row(0).select();
    });

    navigationTable.on('select', function(event, api, type, indexes) {
        var rowData = navigationTable.row(indexes[0]).data();
        navigationTree.load(rowData[0]);
    });

    navigationTable.on('deselect', function() {
        navigationTree.reset();
    });

});
