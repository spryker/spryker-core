/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @param {object} options
 */
var TemplateTable = function (options) {
    var _self = this;
    this.templateTableId = '';
    this.templateTable = {};
    this.slotTable = {};

    $.extend(this, options);

    this.init = function () {
        _self.templateTable = $(_self.templateTableId).DataTable();

        $(_self.templateTableId).find('tbody').on('click', 'tr', _self.tableRowSelect);
        _self.templateTable.on('draw', _self.selectFirstRow);
        _self.templateTable.on('select', _self.loadSlotTable);
    };

    this.tableRowSelect = function (element) {
        if (!$(element.target).is('td')) {
            return;
        }

        _self.templateTable.rows().deselect();
        _self.templateTable.row($(this).index()).select();
    };

    this.selectFirstRow = function (element, settings) {
        _self.getDataTableApi(settings).row(0).select();
    };

    this.loadSlotTable = function (element, api, type, indexes) {
        var rowData = api.row(indexes[0]).data();
        _self.slotTable.loadSlotTableByIdTemplate(rowData[0]);
    };

    this.getDataTableApi = function (settings) {
        return new $.fn.dataTable.Api(settings);
    }
};

/**
 * Open public methods
 */
module.exports = TemplateTable;
