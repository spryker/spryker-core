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
    this.cmsSlotBlocks = {};
    this.blocksTable = {};
    this.slotBlocksForm = {};
    this.blocksChoiceFormSelector = '';
    this.initTableState = [];
    this.isFirstInit = true;

    $.extend(this, options);

    this.init = function () {
        _self.blocksTable = $(_self.blocksTableSelector);
        _self.cmsSlotBlocks = $(_self.cmsSlotBlocksSelector);
        if (!_self.isFirstInit) {
            return
        }
        document.addEventListener('savedBlocksForm', function () {
            _self.getInitTableState(_self.blocksTable.data('ajax'));
        }, false);
        _self.isFirstInit = false;
    };

    this.loadBlocksTable = function (params, idCmsSlotTemplate, idCmsSlot) {
        _self.idCmsSlotTemplate = idCmsSlotTemplate;
        _self.idCmsSlot = idCmsSlot;

        var ajaxUrl = _self.tableBaseUrl + '?' + params;
        _self.blocksTable.data('ajax', ajaxUrl);
        _self.getInitTableState(ajaxUrl);
        _self.blocksTable.DataTable({
            destroy: true,
            ajax: {
                cache: false
            },
            autoWidth: false,
            language: dataTable.defaultConfiguration.language,
            searching: false,
            info: false,
            drawCallback: function() {
                _self.initDataTableListeners(idCmsSlotTemplate, idCmsSlot);
            },
        });
    };

    this.initDataTableListeners = function (idCmsSlotTemplate, idCmsSlot) {
        _self.blocksTable.on('processing.dt', function () {
            _self.overlayToggler(true);
        });
        _self.slotBlocksForm.rebuildForm(idCmsSlotTemplate, idCmsSlot, _self.blocksTable.DataTable().rows().data(), _self.isUnsaved());
        _self.getChangeOrderButtons().each(function () {
            $(this).on('click', _self.changeOrderButtonsHandler.bind(this));
        });
        _self.getRemoveButtons().each(function () {
            $(this).on('click', _self.removeButtonsHandler.bind(this));
        });
    };

    this.updateTable = function (tableApi, tableData) {
        tableApi.rows().remove();
        tableApi.rows.add(tableData).draw();
    };

    this.getInitTableState = function (url) {
        $.get(url).done(function (response) {
            _self.initTableState = response.data;
        });
    };

    this.addRow = function (rowData = {}) {
        rowData = [
            Number(rowData.blockId),
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
    };

    this.getActionButtons = function(blockId) {
        var buttons = $(_self.cmsSlotBlocks.data('actions-buttons-template'));
        var buttonsTemplate = '';

        buttons.each(function() {
            var button = $(this);

            if (!button.is('a')) {
                return;
            }

            if (button.hasClass('btn-view')) {
                button.attr('href', _self.viewBlockUrl + '?id-cms-block=' + blockId);
            }

            buttonsTemplate += button[0].outerHTML + ' ';
        });

        return buttonsTemplate;
    };

    this.getStatusLabel = function (isActive) {
        var labelStatus = isActive ? 'active-label-template' : 'inactive-label-template';

        return _self.cmsSlotBlocks.data(labelStatus);
    };

    this.getStoresLabels = function (stores) {
        var storeTemplate = $(_self.cmsSlotBlocks.data('active-label-template'));
        var storesArray = stores.split(',');

        return storesArray.reduce(function (storesTemplate, store) {
            return storesTemplate + storeTemplate.html(store)[0].outerHTML + ' ';
        }, '');
    };

    this.getTable = function () {
        return {
            api: _self.blocksTable.dataTable().api(),
            data: _self.blocksTable.dataTable().api().data().toArray(),
        }
    };

    this.getChangeOrderButtons = function () {
        return _self.blocksTable.find('.btn[data-direction]');
    };

    this.changeOrderButtonsHandler = function (event) {
        var clickInfo = _self.getClickInfo(event);
        var direction = clickInfo.button.data('direction');
        var isRowFirst = clickInfo.clickedTableRow === 0;
        var isRowLast = clickInfo.clickedTableRow === clickInfo.tableLength - 1;

        if (isRowFirst && direction === 'up' || isRowLast && direction === 'down') {
            return
        }

        _self.changeOrderRow(clickInfo.clickedTableRow, direction);
    };

    this.changeOrderRow = function (rowIndex, direction) {
        var table = _self.getTable();

        switch (direction) {
            case 'up':
                var tempRow = table.data[rowIndex - 1];
                table.data[rowIndex - 1] = table.data[rowIndex];
                table.data[rowIndex] = tempRow;
                break;

            case 'down':
                var tempRow = table.data[rowIndex + 1];
                table.data[rowIndex + 1] = table.data[rowIndex];
                table.data[rowIndex] = tempRow;
                break;
        }

        _self.updateTable(table.api, table.data);
    };

    this.getRemoveButtons = function () {
        return _self.blocksTable.find('.js-slot-block-remove-button');
    };

    this.removeButtonsHandler = function (event) {
        var clickInfo = _self.getClickInfo(event);
        var table = _self.getTable();
        var rowName = table.data[clickInfo.clickedTableRow][1];
        _self.updateChoiseDropdown(rowName);
        table.data.splice(clickInfo.clickedTableRow, 1);
        _self.updateTable(table.api, table.data);
    };

    this.updateChoiseDropdown = function (optionLabel) {
        var choiceDropdown = $(_self.blocksChoiceFormSelector).find('select');
        choiceDropdown.children('option[disabled]')
            .filter(function() { return $(this).text() === optionLabel })
            .attr("disabled", false);
        choiceDropdown.select2();
    };

    this.getClickInfo = function(event) {
        return {
            button: $(event.currentTarget),
            clickedTableRow: $(event.currentTarget).parents('tr').index(),
            tableLength: $(event.currentTarget).parents('tbody').children('tr').length,
        }
    };

    this.isUnsaved = function () {
        var initTableState = _self.initTableState;
        var currentTableState = _self.getTable().data;

        if (initTableState.length !== currentTableState.length) {
            return true
        }

        return initTableState.some(function (item, index) {
            return item[0] !== currentTableState[index][0]
        });
    };

    this.overlayToggler = function (state) {
        $(_self.cmsSlotBlocksOverlaySelector).toggleClass(_self.cmsSlotBlocksOverlayTogglerClass, state);
    };
};

/**
 * Open public methods
 */
module.exports = BlocksTable;
