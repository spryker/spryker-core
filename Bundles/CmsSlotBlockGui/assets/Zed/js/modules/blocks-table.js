/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');

var BlocksTable = function (options) {
    var _self = this;
    this.tableBaseUrl = '';
    this.paramIdCmsSlotTemplate = '';
    this.paramIdCmsSlot = '';
    this.blocksTableSelector = '';
    this.cmsSlotBlocksSelector = '';
    this.viewBlockUrl = '';
    this.cmsSlotBlocks = {};
    this.blocksTable = {};
    this.slotBlocksForm = {};

    $.extend(this, options);

    this.init = function () {
        _self.blocksTable = $(_self.blocksTableSelector);
        _self.cmsSlotBlocks = $(_self.cmsSlotBlocksSelector);
    };

    this.loadBlocksTable = function (params, idCmsSlotTemplate, idCmsSlot) {
        _self.idCmsSlotTemplate = idCmsSlotTemplate;
        _self.idCmsSlot = idCmsSlot;

        var ajaxUrl = _self.tableBaseUrl + '?' + params;
        _self.blocksTable.data('ajax', ajaxUrl);

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
        _self.blocksTable.DataTable().on('draw', function () {
            _self.slotBlocksForm.rebuildForm(idCmsSlotTemplate, idCmsSlot, _self.blocksTable.DataTable().rows().data());
        });
    };

    this.buildParams = function (idCmsSlotTemplate, idCmsSlot) {
        return _self.paramIdCmsSlotTemplate + '=' + idCmsSlotTemplate + '&' + _self.paramIdCmsSlot + '=' + idCmsSlot;
    };

    this.addRow = function (rowData = {}) {
        rowData = [
            rowData.blockId,
            rowData.blockName,
            rowData.validFrom,
            rowData.validTo,
            _self.getStatusLabel(rowData.isActive),
            _self.getStoresLabels(rowData.stores),
            _self.getActionButtons(rowData.blockId),
        ];

        var tableApi = _self.blocksTable.dataTable().api();
        var tableData = tableApi.data().toArray();
        tableData.unshift(rowData);
        tableApi.rows().remove();
        tableApi.rows.add(tableData).draw();
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
        if (isActive) {
            return _self.cmsSlotBlocks.data('active-label-template');
        }

        return _self.cmsSlotBlocks.data('inactive-label-template');
    };

    this.getStoresLabels = function (stores) {
        var storeTemplate = $(_self.cmsSlotBlocks.data('active-label-template'));
        var storesArray = stores.split(',');

        return storesArray.reduce(function (storesTemplate, store) {
            return storesTemplate += storeTemplate.html(store)[0].outerHTML + ' ';
        }, '');
    };
};

/**
 * Open public methods
 */
module.exports = BlocksTable;
