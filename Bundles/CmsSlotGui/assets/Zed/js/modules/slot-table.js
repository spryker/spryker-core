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
    this.dataTableInitCallback = function () {};

    $.extend(this, options);

    this.init = function () {
        _self.slotTable = $(_self.slotTableClass);
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
            this.dataTableInitCallback();
            return;
        }

        _self.slotTable.DataTable().ajax.url(ajaxUrl).load();
    };

    this.activationHandler = function () {
        _self.slotTable.find('.js-slot-activation').on('click', function (event) {
            event.preventDefault();
            var $that = $(this);

            if ($that.data('processing') === true) {
                return false;
            }

            var url = $that.attr('href');
            $that.data('processing', true);

            $.get(url, function (response) {
                if (response.success) {
                    _self.slotTable.DataTable().ajax.reload(null, false);

                    return;
                }

                $that.data('processing', false);
                window.sweetAlert({
                    title: 'Error',
                    text: response.message,
                    html: true,
                    type: 'error'
                });
            });

            return false;
        });
    };

    this.toggleTableRow = function (state) {
        if (!state) {
            _self.slotTable.closest('.wrapper > .row').hide();

            if ($.fn.dataTable.isDataTable(_self.slotTable)) {
                var ajaxUrl = _self.ajaxBaseUrl + '?' + _self.paramIdCmsSlotTemplate + '=0';
                _self.slotTable.data('ajax', ajaxUrl);
                _self.slotTable.attr('data-ajax', ajaxUrl);
                _self.slotTable.DataTable().ajax.url(ajaxUrl).load();
            }

            return;
        }

        _self.slotTable.closest('.wrapper > .row').show();
    }
};

module.exports = SlotTable;
