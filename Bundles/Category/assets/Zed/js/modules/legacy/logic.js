/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

window.serializedList = {};

var categoryHelper = require('./helpers.js');

$(document).ready(function () {
    var triggeredFirstEvent = false;

    $('#root-node-table').on('click', 'tbody tr', function () {
        categoryHelper.showLoaderBar();
        var idCategoryNode = $(this).children('td:first').text();
        SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    $('#category-node-tree').on('click', '.category-tree', function (event) {
        event.preventDefault();
        categoryHelper.showLoaderBar();
        var idCategoryNode = $(this).attr('id').replace('node-', '');
        SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
    });

    $('.gui-table-data-category').dataTable({
        bFilter: false,
        createdRow: function (row, data, index) {
            if (triggeredFirstEvent === false) {
                categoryHelper.showLoaderBar();
                var idCategoryNode = data[0];
                SprykerAjax.getCategoryTreeByIdCategoryNode(idCategoryNode);
                triggeredFirstEvent = true;
            }
        },
    });

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target);
        window.serializedList = window.JSON.stringify(list.nestable('serialize'));
    };

    $('#nestable')
        .nestable({
            group: 1,
            maxDepth: 1,
        })
        .on('change', updateOutput);

    $('.save-categories-order').click(function () {
        SprykerAjax.updateCategoryNodesOrder(serializedList);
    });
});
