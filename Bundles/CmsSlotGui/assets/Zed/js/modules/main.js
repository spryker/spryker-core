/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var TemplateTable = require('./template-table');
var SlotTable = require('./slot-table');

$(document).ready(function() {
    var slotTable = new SlotTable({
        ajaxBaseUrl: '/cms-slot-gui/slot-list/table',
        paramIdCmsSlotTemplate: 'id-cms-slot-template',
        ownershipColumnId: 'spy_cms_slot.content_provider_type',
        slotTableClass: '.js-cms-slot-list-table',
    });

    slotTable.init();

    var templateTable = new TemplateTable({
        templateTableId: '#template-list-table',
        slotTable: slotTable
    });

    templateTable.init();
});
