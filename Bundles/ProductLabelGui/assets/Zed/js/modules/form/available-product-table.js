/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var TableHandler = require('./related-product-table-handler');

var sourceTabSelector = '#available-products-source-tab';
var sourceTableSelector = sourceTabSelector + ' table.table';

var destinationTabSelector = '#available-products-destination-tab';
var destinationTabLabelSelector = destinationTabSelector + '-label';
var destinationTableSelector = destinationTabSelector + '-table';

var checkboxSelector = '.js-abstract-product-checkbox';
var tableHandler;

function initialize()
{
    tableHandler = TableHandler.create(
        sourceTableSelector,
        destinationTableSelector,
        checkboxSelector,
        $(destinationTabLabelSelector).text(),
        destinationTabLabelSelector,
        'js-abstract-products-to-assign-ids-csv-field',
        onRemove
    );

    $(sourceTabSelector + ' .js-select-all-button a').on('click', tableHandler.selectAll);
}

function onRemove()
{
    var $link = $(this);
    var id = $link.data('id');
    var action = $link.data('action');

    var dataTable = $(destinationTableSelector).DataTable();
    dataTable.row($link.parents('tr')).remove().draw();

    tableHandler.getSelector().removeProductFromSelection(id);
    tableHandler.updateSelectedProductsLabelCount();
    $('input[value="' + id + '"]', $(sourceTableSelector)).prop('checked', false);

    return false;
}

module.exports = {
    initialize: initialize
};
