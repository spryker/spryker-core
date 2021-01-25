/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var RelatedCustomerTable = require('./related-customer-table/table');

var sourceTabSelector = '#available-tab';
var sourceTableSelector = sourceTabSelector + ' table.table';

var destinationTabSelector = '#to-be-assigned-tab';
var destinationTabLabelSelector = destinationTabSelector + '-label';
var destinationTableSelector = destinationTabSelector + '-table';

var checkboxSelector = '.js-customer-checkbox';
var tableHandler;

/**
 * @return {void}
 */
function initialize() {
    tableHandler = RelatedCustomerTable.create(
        sourceTableSelector,
        destinationTableSelector,
        checkboxSelector,
        $(destinationTabLabelSelector).text(),
        destinationTabLabelSelector,
        'customerUserConnection_idCustomersToAssign',
        onRemove,
    );

    $(sourceTabSelector + ' .js-select-all-button a').on('click', tableHandler.selectAll);
}

/**
 * @return {boolean}
 */
function onRemove() {
    var $link = $(this);
    var id = $link.data('id');
    var action = $link.data('action');

    var dataTable = $(destinationTableSelector).DataTable();
    dataTable.row($link.parents('tr')).remove().draw();

    tableHandler.getSelector().removeIdFromSelection(id);
    tableHandler.updateSelectedCustomersLabelCount();
    $('input[value="' + id + '"]', $(sourceTableSelector)).prop('checked', false);

    return false;
}

module.exports = {
    initialize: initialize,
};
