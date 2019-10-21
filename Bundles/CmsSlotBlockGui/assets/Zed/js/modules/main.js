/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SlotTable = require('./slot-table');
var BlocksTable = require('./blocks-table');

$(document).ready(function() {
    var blocksTable = new BlocksTable({
        tableBaseUrl: '/cms-slot-block-gui/slot-block/table',
        paramIdCmsSlotTemplate: 'id-cms-slot-template',
        paramIdCmsSlot: 'id-cms-slot',
        blocksTableClass: '.js-cms-slot-block-table',
    });
    //
    // blocksTable.init();

    var slotTable = new SlotTable({
        slotTableClass: '.js-row-list-of-slots table',
        blocksTable: blocksTable,
    });

    slotTable.init();
});