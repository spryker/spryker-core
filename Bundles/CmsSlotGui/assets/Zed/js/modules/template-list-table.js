/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var slotListTable = require('./slot-list-table');
var templateListTable;

/**
 * @param {string} selector
 */
function initialize(selector) {
    templateListTable = $(selector).DataTable();

    $(selector).find('tbody').on('click', 'tr', tableRowSelect);
    templateListTable.on('draw', selectFirstRow);
    templateListTable.on('select', loadSlotTable);
}

/**
 * @param {Event} e
 *
 * @return {void}
 */
function tableRowSelect(e) {
    if (!$(e.target).is('td')) {
        return;
    }

    templateListTable.rows().deselect();
    templateListTable.row($(this).index()).select();
}

/**
 * @return {void}
 */
function selectFirstRow(e, settings) {
    getDataTableApi(settings).row(0).select();
}

/**
 * @return {void}
 */
function loadSlotTable(e, api, type, indexes) {
    var rowData = api.row(indexes[0]).data();
    slotListTable.load(rowData[0]);
}

/**
 * @param {object} settings
 *
 * @returns {DataTable.Api}
 */
function getDataTableApi(settings) {
    return new $.fn.dataTable.Api(settings);
}

/**
 * Open public methods
 */
module.exports = {
    initialize: initialize
};
