/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var categoryTree = require('./category-tree');
var categoryTable;

/**
 * @param {string} selector
 */
function initialize(selector) {
    categoryTable = $(selector).DataTable();

    categoryTree.initialize();

    $(selector).find('tbody').on('click', 'tr', tableRowSelect);
    categoryTable.on('draw', selectFirstRow);
    categoryTable.on('select', loadCategoryTree);
    categoryTable.on('deselect', resetCategoryTree);
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

    categoryTable.rows().deselect();
    categoryTable.row($(this).index()).select();
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
function loadCategoryTree(e, api, type, indexes) {
    var rowData = api.row(indexes[0]).data();
    categoryTree.load(rowData[0]);
}

/**
 * @return {void}
 */
function resetCategoryTree(e, api) {
    categoryTree.reset();
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
