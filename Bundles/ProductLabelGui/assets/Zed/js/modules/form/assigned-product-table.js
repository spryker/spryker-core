/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var TableHandler = require('./related-product-table-handler');

var sourceTabSelector = '#assigned-products-source-tab';
var sourceTableSelector = sourceTabSelector + ' table.table';

var destinationTabSelector = '#assigned-products-destination-tab';
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
        'js-abstract-products-to-de-assign-ids-csv-field',
        onRemove
    );

    tableHandler.getInitialCheckboxCheckedState = function() {
        return TableHandler.CHECKBOX_CHECKED_STATE_CHECKED;
    };

    $(sourceTabSelector + ' .js-toggle-selection-button a').on('click', tableHandler.toggleSelection);
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
    $('input[value="' + id + '"]', $(sourceTableSelector)).prop('checked', true);

    return false;
}

module.exports = {
    initialize: initialize
};
