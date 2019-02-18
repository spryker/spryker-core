/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var navigationTree = require('./navigation-tree');
var navigationTable;

/**
 * @param {string} selector
 */
function initialize(selector) {
    navigationTable = $(selector).DataTable();

    navigationTree.initialize();

    $(selector).find('tbody').on('click', 'tr', tableRowSelect);
    navigationTable.on('draw', selectFirstRow);
    navigationTable.on('select', loadNavigationTree);
    navigationTable.on('deselect', resetNavigationTree);
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

    navigationTable.rows().deselect();
    navigationTable.row($(this).index()).select();
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
function loadNavigationTree(e, api, type, indexes) {
    var rowData = api.row(indexes[0]).data();
    navigationTree.load(rowData[0]);
}

/**
 * @return {void}
 */
function resetNavigationTree(e, api) {
    navigationTree.reset();
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
