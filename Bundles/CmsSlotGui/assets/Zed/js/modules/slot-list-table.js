/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');
var slotListTable = $('table.cms-slot-list-table');
var ownershipColumnId = 'spy_cms_slot.content_provider_type';
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
        "autoWidth": false,
        'language': dataTable.defaultConfiguration.language,
        'drawCallback': function() {
            var api = this.api();

            displayOwnershipColumn(api);
            activationHandler();
        },
    });
}

function displayOwnershipColumn(api) {
    var ownershipColumnIndex = null;
    var ownerships = [];
    var ownershipColumn = null;

    api.columns().header().each(function (e, i) {
        if ($(e).attr("id") === ownershipColumnId) {
            ownershipColumnIndex = i;
        }
    });

    if (ownershipColumnIndex !== null) {
        ownershipColumn = api.table().columns(ownershipColumnIndex);
        ownershipColumn.visible(true);

        ownerships = ownershipColumn.data()[0].filter(function (value, index, self) {
            return self.indexOf(value) === index;
        });

        if (ownerships.length === 1) {
            ownershipColumn.visible(false);
        }
    }
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
