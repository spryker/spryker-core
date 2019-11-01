/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @param {object} options
 */
var SlotBlocks = function (options) {
    var _self = this;
    this.slotClass = '';
    this.slotTableClass = '';
    this.blockContainerClass = '',
    this.baseUrl = '';
    this.slotTable = {};
    this.blocksTable = {};
    this.blocksChoice = {};
    this.slotBlocksForm = {};
    this.paramIdCmsSlotTemplate = '';
    this.paramIdCmsSlot = '';

    $.extend(this, options);

    this.init = function () {
        _self.slotTable = $(_self.slotTableClass).DataTable();

        $(_self.slotTableClass).find('tbody').on('click', 'tr', _self.tableRowSelect);
        _self.slotTable.on('draw', _self.selectFirstRow);
        _self.slotTable.on('select', _self.loadBlocksTable);
    };

    this.tableRowSelect = function (element) {
        if (!$(element.target).is('td')) {
            return;
        }

        var cellIndex = $(this).index();
        if (_self.blocksTable.isUnsaved()) {
            var cmsSlotBlock = _self.blocksTable.cmsSlotBlocks;
            window.sweetAlert({
                title: cmsSlotBlock.data('alert-title'),
                text: cmsSlotBlock.data('alert-text'),
                type: 'warning',
                showCancelButton: true,
                html: false,
            }, function () {
                _self.updateRow(cellIndex);
            });
            return;
        }
        _self.updateRow(cellIndex);
    };

    this.updateRow = function (index) {
        _self.slotTable.rows().deselect();
        _self.slotTable.row(index).select();
    };

    this.selectFirstRow = function () {
        _self.slotTable.row(0).select();
    };

    this.loadBlocksTable = function (element, api, type, indexes) {
        var idCmsSlotTemplate = $('#template-list-table').dataTable().api().rows( { selected: true } ).data()[0][0];
        var idCmsSlot = api.row(indexes[0]).data()[0];
        var paramsCollection = {};
        paramsCollection[_self.paramIdCmsSlotTemplate] = idCmsSlotTemplate;
        paramsCollection[_self.paramIdCmsSlot] = idCmsSlot;
        var params = $.param(paramsCollection);
        $.get(_self.baseUrl + '?' + params).done(function (html) {
            $(_self.blockContainerClass).remove();
            $(html).insertAfter($(_self.slotClass));

            _self.blocksTable.init();
            _self.blocksTable.overlayToggler(false);
            _self.blocksChoice.init();
            _self.slotBlocksForm.init();
            _self.blocksTable.loadBlocksTable(params, idCmsSlotTemplate, idCmsSlot);
        });
    };

    this.getDataTableApi = function () {
        return _self.slotTable;
    };
};

/**
 * Open public methods
 */
module.exports = SlotBlocks;
