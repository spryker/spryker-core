/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');
var slotListTable = $('table.cms-slot-list-table');
var config = {
    ajaxBaseUrl: '/cms-slot-gui/slot-list/table',
    paramIdCmsSlotTemplate: 'id-cms-slot-template'
};

function load(rowIndex) {
    var ajaxUrl = config.ajaxBaseUrl + '?' + config.paramIdCmsSlotTemplate + '=' + rowIndex;
    slotListTable.DataTable().destroy();
    slotListTable.data('ajax', ajaxUrl);

    slotListTable.DataTable({
        'ajax': ajaxUrl,
        'lengthChange': false,
        'language': dataTable.defaultConfiguration.language,
    });

    slotListTable.on('draw.dt', function () {
        activationHandler();
    });
}

function activationHandler() {
    $('a.slot-activation').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.get(url, function (response) {
            if (response.success) {
                slotListTable.DataTable().ajax.reload(null, false);
            }
        });

        return false;
    });
}

/**
 * Open public methods
 */
module.exports = {
    load: load
};
