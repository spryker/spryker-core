/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @param {object} options
 */
var SlotTable1 = function (options) {
    var _self = this;
    this.slotTableClass = '';
    this.slotTable = {};
    this.blocksTable = {};

    $.extend(this, options);

    this.init = function () {

        _self.slotTable = $(_self.slotTableClass).DataTable();

        $(_self.slotTableClass).find('tbody').on('click', 'tr', _self.tableRowSelect);
        _self.slotTable.on('init', _self.selectFirstRow);
        _self.slotTable.on('select', _self.loadBlocksTable);
    };

    this.tableRowSelect = function (element) {
        if (!$(element.target).is('td')) {
            return;
        }

        _self.slotTable.rows().deselect();
        _self.slotTable.row($(this).index()).select();
    };

    this.selectFirstRow = function (element, settings) {
        _self.slotTable.row(0).select();
    };

    this.loadBlocksTable = function (element, api, type, indexes) {
        var idCmsSlotTemplate = $('#template-list-table').dataTable().api().rows( { selected: true } ).data()[0][0];
        var idCmsSlot = api.row(indexes[0]).data()[0];

        _self.blocksTable.loadSlotBlocks(idCmsSlotTemplate, idCmsSlot);
    };

    this.getDataTableApi = function (settings) {
        return _self.slotTable;
    };
};

/**
 * Open public methods
 */
module.exports = SlotTable1;
