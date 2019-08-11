/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @param {object} options
 */
var TemplateTable = function (options) {
    this.templateTableId = '';
    this.slotTable = {};

    $.extend(this, options);

    this.init = function () {
        this.templateTable = $(this.templateTableId).DataTable();

        $(this.templateTableId).find('tbody').on('click', 'tr', this.tableRowSelect);
        this.templateTable.on('draw', this.selectFirstRow);
        this.templateTable.on('select', this.loadSlotTable);
    };

    this.tableRowSelect = function (element) {
        if (!$(element.target).is('td')) {
            return;
        }

        this.templateTable.rows().deselect();
        this.templateTable.row($(this).index()).select();
    };

    this.selectFirstRow = function (element, settings) {
        this.getDataTableApi(settings).row(0).select();
    };

    this.loadSlotTable = function (element, api, type, indexes) {
        var rowData = api.row(indexes[0]).data();
        this.slotTable.load(rowData[0]);
    };

    this.getDataTableApi = function (settings) {
        return new $.fn.dataTable.Api(settings);
    }
};

/**
 * Open public methods
 */
module.exports = TemplateTable;
