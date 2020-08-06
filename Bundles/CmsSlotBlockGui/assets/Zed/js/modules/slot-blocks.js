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
    this.cmsSlotBlockContentProviderSelector = '';
    this.cmsSlotBlockContentProvider = '';
    this.contentProviderAttribute = '';
    this.paramIdCmsSlotTemplate = '';
    this.paramIdCmsSlot = '';
    this.isFirstInit = true;

    $.extend(this, options);

    this.init = function () {
        _self.cmsSlotBlockContentProvider = $.trim($(_self.cmsSlotBlockContentProviderSelector).val());
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
        _self.updateRow(cellIndex);
    };

    this.updateRow = function (index) {
        _self.$slotTable.rows().deselect();
        _self.$slotTable.row(index).select();
    };

    this.selectFirstRow = function () {
        var isSlotTableEnabled = _self.$slotTable.rows().count() !== 0 && $(_self.slotTableSelector).is(':visible');
        _self.blocksTable.toggleTableRow(isSlotTableEnabled);
        _self.$slotTable.row(0).select();
    };

    this.loadBlocksTable = function (element, api, type, indexes) {
        var templateTableApi = $('#template-list-table').dataTable().api();

        if (templateTableApi.rows( { selected: true } ).count() === 0) {
            return;
        }

        if (!_self.isCmsSlotBlockContentProvider(api, indexes)) {
            _self.blocksTable.toggleTableRow(false);
            return;
        }

        var idCmsSlotTemplate = templateTableApi.rows( { selected: true } ).data()[0][0];
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
                _self.loadBlocksTable(element, api, type, indexes);
            };
        });
        if (!_self.isFirstInit) {
            return;
        }
        _self.isFirstInit = false;
    };

    this.getDataTableApi = function () {
        return _self.$slotTable;
    };

    this.isCmsSlotBlockContentProvider = function (api, indexes) {
        return api.row(indexes[0])
            .nodes()
            .to$()
            .find("[" + _self.contentProviderAttribute + "='" + _self.cmsSlotBlockContentProvider + "']")
            .length;
    };
};

module.exports = SlotBlocks;
