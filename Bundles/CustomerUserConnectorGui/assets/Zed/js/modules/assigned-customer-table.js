/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var RelatedCustomerTable = require('./related-customer-table/table');

var sourceTabSelector = '#assigned-tab';
var sourceTableSelector = sourceTabSelector + ' table.table';

var destinationTabSelector = '#deassigned-tab';
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
        'customerUserConnection_idCustomersToDeAssign',
        onRemove,
    );

    tableHandler.getInitialCheckboxCheckedState = function () {
        return RelatedCustomerTable.CHECKBOX_CHECKED_STATE_CHECKED;
    };

    $(sourceTabSelector + ' .js-de-select-all-button a').on('click', tableHandler.deSelectAll);
}

/**
 * @returns {boolean}
 */
function onRemove() {
    var $link = $(this);
    var id = $link.data('id');
    var action = $link.data('action');

    var dataTable = $(destinationTableSelector).DataTable();
    dataTable.row($link.parents('tr')).remove().draw();

    tableHandler.getSelector().removeIdFromSelection(id);
    tableHandler.updateSelectedCustomersLabelCount();
    $('input[value="' + id + '"]', $(sourceTableSelector)).prop('checked', true);

    return false;
}

module.exports = {
    initialize: initialize,
};
