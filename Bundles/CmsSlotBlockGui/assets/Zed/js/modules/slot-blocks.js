/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SlotBlocks = function (options) {
    var _self = this;
    this.slotSelector = '';
    this.slotTableSelector = '';
    this.blockContainerSelector = '';
    this.baseUrl = '';
    this.$slotTable = {};
    this.blocksTable = {};
    this.blocksChoice = {};
    this.slotBlocksForm = {};
    this.paramIdCmsSlotTemplate = '';
    this.paramIdCmsSlot = '';
    this.isFirstInit = true;

    $.extend(this, options);

    this.init = function () {
        _self.$slotTable = $(_self.slotTableSelector).DataTable();
        $(_self.slotTableSelector).find('tbody').on('click', 'tr', _self.tableRowSelect);
        _self.$slotTable.on('draw', _self.selectFirstRow);
        _self.$slotTable.on('select', _self.loadBlocksTable);
    };

    this.tableRowSelect = function (element) {
        if (!$(element.target).is('td')) {
            return;
        }

        var cellIndex = $(this).index();
        if (!_self.blocksTable.isUnsaved()) {
            _self.updateRow(cellIndex);
            return;
        }

        _self.showAlert();
    };

    this.updateRow = function (index) {
        _self.$slotTable.rows().deselect();
        _self.$slotTable.row(index).select();
    };

    this.selectFirstRow = function () {
        _self.$slotTable.row(0).select();
    };

    this.loadBlocksTable = function (element, api, type, indexes) {
        var idCmsSlotTemplate = $('#template-list-table').dataTable().api().rows( { selected: true } ).data()[0][0];
        var idCmsSlot = api.row(indexes[0]).data()[0];
        var paramsCollection = {};
        paramsCollection[_self.paramIdCmsSlotTemplate] = idCmsSlotTemplate;
        paramsCollection[_self.paramIdCmsSlot] = idCmsSlot;
        var params = $.param(paramsCollection);
        $.get(_self.baseUrl + '?' + params).done(function (html) {
            $(_self.blockContainerSelector).remove();
            $(html).insertAfter($(_self.slotSelector));

            _self.blocksTable.init();
            _self.blocksTable.overlayToggler(false);
            _self.blocksChoice.init();
            _self.slotBlocksForm.init();
            _self.blocksTable.loadBlocksTable(params, idCmsSlotTemplate, idCmsSlot);
            _self.blocksTable.resetHandlerCallback = function() {
                _self.slotBlocksForm.isStateChanged = false;
                _self.loadBlocksTable(element, api, type, indexes);
            };
        });
        if (!_self.isFirstInit) {
            return;
        }
        _self.isFirstInit = false;
        _self.$slotTable.on('preDraw', function () {
            if (_self.blocksTable.isUnsaved()) {
                console.log(_self.$slotTable);
                _self.showAlert();
                return false;
            }
        });
    };

    this.getDataTableApi = function () {
        return _self.$slotTable;
    };

    this.showAlert = function () {
        var $cmsSlotBlock = _self.blocksTable.$cmsSlotBlocks;
        window.sweetAlert({
            title: $cmsSlotBlock.data('alert-title'),
            html: false,
            showCloseButton: true,
            customClass: 'cms-slot-blocks-alert',
            confirmButtonColor: '#1ab394',
            confirmButtonText: $cmsSlotBlock.data('alert-go-back-button'),
        });
    }
};

module.exports = SlotBlocks;
