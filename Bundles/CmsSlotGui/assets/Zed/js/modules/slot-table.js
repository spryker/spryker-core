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

    $.extend(this, options);

    this.init = function () {
        _self.slotTable = $(_self.slotTableClass);
    };

    this.loadSlotTableByIdTemplate = function (idTemplate) {
        var ajaxUrl = _self.ajaxBaseUrl + '?' + _self.paramIdCmsSlotTemplate + '=' + idTemplate;
        _self.slotTable.data('ajax', ajaxUrl);

        _self.slotTable.DataTable({
            destroy: true,
            ajax: {
                cache: false
            },
            autoWidth: false,
            language: dataTable.defaultConfiguration.language,
            drawCallback: function() {
                _self.activationHandler();
            },
        });
    };

    this.activationHandler = function () {
        $('.js-slot-activation').on('click', function(event) {
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
        });
    }
};

/**
 * Open public methods
 */
module.exports = SlotTable;
