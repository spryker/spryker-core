/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');

var SlotTable = function (options) {
    var _self = this;
    this.ajaxBaseUrl = '';
    this.paramIdCmsSlotTemplate = '';
    this.ownershipColumnId = '';
    this.slotTableClass = '';
    this.slotTable = {};
    this.dataTableInit = false;

    $.extend(this, options);

    this.init = function () {
        _self.slotTable = $(_self.slotTableClass);

        $(_self.slotTableClass).on('click', '.js-slot-activation', _self.activationHandler);
    };

    this.loadSlotTableByIdTemplate = function (idTemplate) {
        var ajaxUrl = _self.ajaxBaseUrl + '?' + _self.paramIdCmsSlotTemplate + '=' + idTemplate;

        if (!_self.dataTableInit) {
            _self.slotTable.data('ajax', ajaxUrl);

            _self.slotTable.DataTable({
                ajax: {
                    cache: false
                },
                autoWidth: false,
                language: dataTable.defaultConfiguration.language,
                drawCallback: function() {
                    _self.activationHandler();
                },
            });

            _self.dataTableInit = true;
        }

        _self.slotTable.DataTable().ajax.url(ajaxUrl).load();
    };

    this.activationHandler = function () {
        event.preventDefault();
        var url = $(this).attr('href');

        $.get(url, function (response) {
            if (response.success) {
                _self.slotTable.DataTable().ajax.reload(null, false);

                return;
            }

            window.sweetAlert({
                title: 'Error',
                text: response.message,
                html: true,
                type: 'error'
            });
        });

        return false;
    }
};

/**
 * Open public methods
 */
module.exports = SlotTable;
