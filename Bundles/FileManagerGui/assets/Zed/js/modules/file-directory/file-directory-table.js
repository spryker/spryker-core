/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var fileDirectoryTree = require('./file-directory-tree');
var fileDirectoryTable;

/**
 * @param {string} selector
 */
function initialize(selector) {
    fileDirectoryTable = $(selector).DataTable();

    fileDirectoryTree.initialize();

    $(selector).find('tbody').on('click', 'tr', tableRowSelect);
    fileDirectoryTable.on('draw', selectFirstRow);
    fileDirectoryTable.on('select', loadNavigationTree);
    fileDirectoryTable.on('deselect', resetNavigationTree);
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

    fileDirectoryTable.rows().deselect();
    fileDirectoryTable.row($(this).index()).select();
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
    fileDirectoryTree.load(rowData[0]);
}

/**
 * @return {void}
 */
function resetNavigationTree(e, api) {
    fileDirectoryTree.reset();
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
