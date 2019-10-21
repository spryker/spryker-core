/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');

var BlocksTable = function (options) {
    var _self = this;
    this.inited = false;
    this.tableBaseUrl = '';
    this.paramIdCmsSlotTemplate = '';
    this.paramIdCmsSlot = '';
    this.blocksTableClass = '';
    this.blocksTable = {};

    $.extend(this, options);

    this.init = function () {
        _self.blocksTable = $(_self.blocksTableClass);
        _self.inited = true;
    };

    this.loadSlotBlocks = function (idCmsSlotTemplate, idCmsSlot) {
        var params = _self.paramIdCmsSlotTemplate + '=' + idCmsSlotTemplate + '&' + _self.paramIdCmsSlot + '=' + idCmsSlot;

        if (_self.inited) {
            _self.loadBlocksTable(params);

            return;
        }

        _self.loadSlotBlocksTemplate(params);

    };

    this.loadSlotBlocksTemplate = function (params) {
        $.get('/cms-slot-block-gui/slot-block/index' + '?' + params).done(function (html) {
            $(html).insertAfter($('.js-row-list-of-slots'));

            _self.init();
            _self.loadBlocksTable(params);
        });
    };

    this.loadBlocksTable = function (params) {
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
        });
    };
};

/**
 * Open public methods
 */
module.exports = BlocksTable;
