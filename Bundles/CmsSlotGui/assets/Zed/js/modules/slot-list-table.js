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
    slotListTable.data('ajax', ajaxUrl);

    slotListTable.DataTable({
        'destroy': true,
        'ajax': {
            "cache": false
        },
        'lengthChange': false,
        'language': dataTable.defaultConfiguration.language,
        "drawCallback": function() {
            var api = this.api();

            api.table().columns().every(function () {
                this.visible(true);

                if (this.data()[0] === null) {
                    this.visible(false);
                }
            });

            activationHandler();
        },
    });
}

function activationHandler() {
    $('a.slot-activation').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.get(url, function (response) {
            if (response.success) {
                slotListTable.DataTable().ajax.reload(null, false);
                slotListTable.DataTable().columns.adjust().draw();
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
