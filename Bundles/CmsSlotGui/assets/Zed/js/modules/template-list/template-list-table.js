/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var templateListTable;

/**
 * @param {string} selector
 */
function initialize(selector) {
    templateListTable = $(selector).DataTable();

    $(selector).find('tbody').on('click', 'tr', tableRowSelect);
    templateListTable.on('draw', selectFirstRow);
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
