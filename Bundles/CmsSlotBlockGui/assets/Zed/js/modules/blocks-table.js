/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');

var BlocksTable = function (options) {
    var _self = this;
    this.tableBaseUrl = '';
    this.blocksTableSelector = '';
    this.cmsSlotBlocksSelector = '';
    this.cmsSlotBlocksOverlaySelector = '';
    this.cmsSlotBlocksOverlayTogglerClass = '';
    this.viewBlockUrl = '';
    this.$cmsSlotBlocks = {};
    this.$blocksTable = {};
    this.slotBlocksForm = {};
    this.blocksChoiceFormSelector = '';
    this.$blocksChoiceDropDown = '';
    this.initOptionsState = [];
    this.initTableState = [];
    this.isFirstInit = true;
    this.isFirstTableRender = true;
    this.changeOrderButtonSelector = '.btn[data-direction]';
    this.removeButtonSelector = '.js-slot-block-remove-button';
    this.resetButtonSelector = '.js-slot-block-reset-button';
    this.rowUnsavedOverlaySelector = '.js-row-unsaved-overlay .ibox-content';
    this.selectedRowIndex = 0;
    this.tableIsUnsaved = false;
    this.modifiedBlocks = [];

    $.extend(this, options);

    this.init = function () {
        _self.$blocksTable = $(_self.blocksTableSelector);
        _self.initTableState = [];
        _self.$cmsSlotBlocks = $(_self.cmsSlotBlocksSelector);
        _self.$blocksChoiceDropDown = $(_self.blocksChoiceFormSelector).find('select');
        _self.isFirstTableRender = true;
        _self.slotBlocksForm.resolveIsUnsavedCallback = _self.resolveIsUnsaved;
        if (!_self.isFirstInit) {
            return;
        }
        $(document).on('savedBlocksForm', function () {
            _self.setInitTableState();
            _self.tableRowSelect();
            _self.resetModifiedBlocks();
        });
        _self.isFirstInit = false;
        _self.setInitOptionsState();
    };

    this.loadBlocksTable = function (params, idCmsSlotTemplate, idCmsSlot) {
        _self.idCmsSlotTemplate = idCmsSlotTemplate;
        _self.idCmsSlot = idCmsSlot;

        var ajaxUrl = _self.tableBaseUrl + '?' + params;
        _self.$blocksTable.data('ajax', ajaxUrl);
        _self.$blocksTable.DataTable({
            destroy: true,
            ajax: {
                cache: false
            },
            autoWidth: false,
            language: dataTable.defaultConfiguration.language,
            searching: false,
            info: false
        });
        _self.$blocksTable.DataTable().on('draw', function(){
            if (_self.isFirstTableRender === true) {
                _self.setInitTableState();
            }
            _self.resolveIsUnsaved(_self.isUnsaved());
            _self.initDataTableListeners(idCmsSlotTemplate, idCmsSlot);
            _self.tableRowSelect();
        });
        _self.$blocksTable.DataTable().on('preInit', function(){
            _self.isFirstTableRender = true;
        });
        _self.$blocksTable.DataTable().on('init', function(){
            _self.isFirstTableRender = false;
        });
    };

    this.initDataTableListeners = function (idCmsSlotTemplate, idCmsSlot) {
        _self.$blocksTable.on('processing.dt', function () {
            _self.overlayToggler(true);
        });
        _self.slotBlocksForm.rebuildForm(idCmsSlotTemplate, idCmsSlot, _self.$blocksTable.DataTable().rows().data(), _self.isUnsaved());
        _self.isFirstTableRender = false;
        _self.initActionButtonsListeners();
        $(_self.$blocksTable).find('tbody').on('click', 'tr', _self.tableRowSelect);
    };

    this.initActionButtonsListeners = function () {
        _self.$blocksTable.find(_self.changeOrderButtonSelector).on('click', _self.changeOrderButtonsHandler);
        _self.$blocksTable.find(_self.removeButtonSelector).on('click', _self.removeButtonsHandler);
        _self.$cmsSlotBlocks.find(_self.resetButtonSelector).off('click.resetSlotBlocks').on('click.resetSlotBlocks', _self.resetButtonsHandler);
    };

    this.updateTable = function (tableApi, tableData) {
        tableApi.rows().remove();
        tableApi.rows.add(tableData).draw();
        tableApi.rows( { selected: true } ).deselect();
        tableApi.row(_self.selectedRowIndex).select();
    };

    this.setInitTableState = function () {
        _self.initTableState = _self.getTable().data;
    };

    this.isBlockModified = function (id) {
        return _self.modifiedBlocks.includes(id);
    }

    this.resetModifiedBlocks = function () {
        _self.modifiedBlocks = [];
    }

    this.toggleIsModified = function (id) {
        const blockIndex = _self.modifiedBlocks.indexOf(id);
        if (blockIndex > -1) {
            _self.modifiedBlocks.splice(blockIndex, 1);
        } else {
            _self.modifiedBlocks.push(id);
        }
    }

    this.addRow = function (rowData = {}) {
        const blockId = Number(rowData.blockId);
        rowData = [
            blockId,
            rowData.blockName,
            rowData.validFrom,
            rowData.validTo,
            _self.getStatusLabel(rowData.isActive),
            _self.getStoresLabels(rowData.stores),
            _self.getActionButtons(rowData.blockId),
        ];

        var table = _self.getTable();
        table.data.unshift(rowData);
        _self.updateTable(table.api, table.data);
        _self.toggleIsModified(blockId);
    };

    this.getActionButtons = function(blockId) {
        var $buttons = $(_self.$cmsSlotBlocks.data('actions-buttons-template'));
        var buttonsTemplate = '';

        $buttons.each(function() {
            var $button = $(this);

            if (!$button.is('a')) {
                return;
            }

            if ($button.hasClass('btn-view')) {
                $button.attr('href', _self.viewBlockUrl + '?id-cms-block=' + blockId);
            }

            buttonsTemplate += $button[0].outerHTML + ' ';
        });

        return buttonsTemplate;
    };

    this.getStatusLabel = function (isActive) {
        var statusLabel = isActive ? 'active-label-template' : 'inactive-label-template';

        return _self.$cmsSlotBlocks.data(statusLabel);
    };

    this.getStoresLabels = function (stores) {
        var $storeTemplate = $(_self.$cmsSlotBlocks.data('active-label-template'));
        var storesArray = stores.split(',');

        return storesArray.reduce(function (storesTemplate, store) {
            return storesTemplate + $storeTemplate.html(store)[0].outerHTML + ' ';
        }, '');
    };

    this.getTable = function () {
        return {
            api: _self.$blocksTable.dataTable().api(),
            data: _self.$blocksTable.dataTable().api().data().toArray(),
        };
    };

    this.changeOrderButtonsHandler = function (event) {
        var clickInfo = _self.getClickInfo(event);
        var direction = clickInfo.$button.data('direction');
        var isRowFirst = clickInfo.$clickedTableRow === 0;
        var isRowLast = clickInfo.$clickedTableRow === clickInfo.$tableLength - 1;

        if (isRowFirst && direction === 'up' || isRowLast && direction === 'down') {
            return;
        }

        _self.changeOrderRow(clickInfo.$clickedTableRow, direction);
    };

    this.changeOrderRow = function (rowIndex, direction) {
        var table = _self.getTable();
        var newRowIndex = null;

        switch (direction) {
            case 'up':
                newRowIndex = rowIndex - 1;
                var tempRow = table.data[newRowIndex];
                table.data[newRowIndex] = table.data[rowIndex];
                table.data[rowIndex] = tempRow;
                break;

            case 'down':
                newRowIndex = rowIndex + 1;
                var tempRow = table.data[newRowIndex];
                table.data[newRowIndex] = table.data[rowIndex];
                table.data[rowIndex] = tempRow;
                break;
        }

        _self.updateTable(table.api, table.data);
    };

    this.removeButtonsHandler = function (event) {
        var clickInfo = _self.getClickInfo(event);
        var table = _self.getTable();
        var rowId = table.data[clickInfo.$clickedTableRow][0];
        _self.updateChoiceDropdown(rowId);
        table.data.splice(clickInfo.$clickedTableRow, 1);
        _self.updateTable(table.api, table.data);
    };

    this.setInitOptionsState = function () {
        _self.$blocksChoiceDropDown.find('option').each(function () {
            _self.initOptionsState.push($(this).prop('disabled'));
        });
    };

    this.updateChoiceDropdown = function (blockId) {
        _self.toggleIsModified(Number(blockId));
    };

    this.resetChoiceDropdown = function () {
        _self.$blocksChoiceDropDown.children('option').each(function (index) {
            $(this).prop('disabled', _self.initOptionsState[index]);
        });
        _self.$blocksChoiceDropDown.select2();
    };

    this.resetHandlerCallback = function () {};

    this.resetButtonsHandler = function () {
        if (!_self.isUnsaved()) {
            return;
        }

        _self.tableIsUnsaved = false;
        _self.slotBlocksForm.isStateChanged = false;
        _self.toggleRowOverlay(false);
        _self.toggleResetButton(false);
        _self.resetHandlerCallback();
    };

    this.toggleRowOverlay = function (state = _self.isUnsaved()) {
        var $rowUnsavedOverlay = $(_self.rowUnsavedOverlaySelector);
        state = state === undefined ? true : !!state;
        $rowUnsavedOverlay.each(function(i, element) {
            var $element = $(element);
            if (state && $element.find('.js-row-overlay').length === 0) {
                var $overlay = $("<div/>").addClass('js-row-overlay');
                $element.append($overlay).addClass('js-row-overlayed');
                $overlay.on('click', function(){
                    _self.showAlert();
                });
                $overlay.show();
            } else if(!state) {
                $element.removeClass('js-row-overlayed').find('.js-row-overlay').remove();
            }
        });
    };

    this.toggleResetButton = function (state = _self.isUnsaved()) {
        $(_self.resetButtonSelector).toggleClass('hidden', !state);
    };

    this.getClickInfo = function(event) {
        return {
            $button: $(event.currentTarget),
            $clickedTableRow: $(event.currentTarget).parents('tr').index(),
            $tableLength: $(event.currentTarget).parents('tbody').children('tr').length,
        }
    };

    this.isUnsaved = function () {
        return _self.tableIsUnsaved;
    };

    this.resolveIsUnsaved = function (isUnsaved) {
        if (!isUnsaved) {
            isUnsaved = _self.checkTableState();
        }

        if (isUnsaved !== _self.tableIsUnsaved) {
            _self.toggleRowOverlay(isUnsaved);
            _self.toggleResetButton(isUnsaved);
        }

        _self.tableIsUnsaved = isUnsaved;
        return _self.tableIsUnsaved;
    };

    this.checkTableState = function () {
        var initTableState = _self.initTableState;
        var currentTableState = _self.getTable().data;

        if (initTableState.length !== currentTableState.length) {
            return true;
        }

        return initTableState.some(function (item, index) {
            return item[0] !== currentTableState[index][0]
        });
    };

    this.overlayToggler = function (state) {
        $(_self.cmsSlotBlocksOverlaySelector).toggleClass(_self.cmsSlotBlocksOverlayTogglerClass, state);
    };

    this.tableRowSelect = function (element) {
        var rowIndex = _self.selectedRowIndex;

        if ($(_self.$blocksTable).DataTable().rows().count() < 1) {
            return;
        }

        if (element !== undefined && $(element.target).is('td')) {
            rowIndex = $(this).index();
            _self.selectedRowIndex = rowIndex;
        }

        var row = _self.$blocksTable.DataTable().row(rowIndex);
        _self.$blocksTable.DataTable().rows().deselect();
        row.select();
        var idCmsBlock = row.data()[0];

        $('.js-cms-slot-block-form-item').hide();

        var cmsSlotBlockFormItem = $('#js-cms-slot-block-form-item-' + idCmsBlock);
        _self.slotBlocksForm.toggleEnablementFromBlocksTable(cmsSlotBlockFormItem);
        cmsSlotBlockFormItem.show();
    };

    this.showAlert = function () {
        var $cmsSlotBlock = _self.$cmsSlotBlocks;
        window.sweetAlert({
            title: $cmsSlotBlock.data('alert-title'),
            html: false,
            showCloseButton: true,
            customClass: 'cms-slot-blocks-alert',
            confirmButtonColor: '#1ab394',
            confirmButtonText: $cmsSlotBlock.data('alert-go-back-button'),
        });
    };

    this.toggleTableRow = function (state) {
        var $blocksTable = $(_self.blocksTableSelector);

        if (!state) {
            $blocksTable.closest('.wrapper > .row').hide();
            _self.toggleRowOverlay(false);
            _self.toggleResetButton(false);

            return;
        }

        $blocksTable.closest('.wrapper > .row').show();
    }
};

module.exports = BlocksTable;
