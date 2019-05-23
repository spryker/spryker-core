/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var tree = require('./tree.js');
var progressBar = require('../shared/progress-bar');

$(document).ready(function() {
    progressBar.setSelector('#category-tree-progress-bar');

    var rootNodeDataTable = jQuery('.gui-table-data-category').DataTable({
        bFiltered: false,
        select: {
            style: 'single',
            selector: 'td:not(:last-child)'
        }
    });

    var categorySearchInput = jQuery('.gui-table-data-category').parents('.dataTables_wrapper').find('input[type="search"]');
    var categoryTimeOutId = 0;

    if(categorySearchInput.length) {
        categorySearchInput
        .unbind()
        .bind("input", function(e) {
            var self = this;

            clearTimeout(categoryTimeOutId);
            categoryTimeOutId = setTimeout(function() {
                rootNodeDataTable.search(self.value).draw();
            }, 1000);
            return;
        });
    }

    rootNodeDataTable.on('draw', function(event, settings) {
        if (settings.json.data.length === 0) {
            return;
        }

        rootNodeDataTable.row(0).select();
    });

    rootNodeDataTable.on('select', function(event, api, type, indexes) {
        var data = rootNodeDataTable.row(indexes[0]).data();
        tree.load(data[0], jQuery('#category-tree'), progressBar);
    });

    rootNodeDataTable.on('deselect', function(event, api, type, indexes) {
        tree.reset(jQuery('#category-tree'));
    });
});
