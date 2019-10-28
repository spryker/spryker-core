/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SlotBlocks = require('./slot-blocks');
var BlocksTable = require('./blocks-table');
var BlocksChoice = require('./blocks-choice');
var SlotBlocksForm = require('./slot-blocks-form');

$(document).ready(function() {
    var slotBlocksForm = new SlotBlocksForm({
        cmsSlotBlocksSelector: '.js-cms-slot-blocks',
    });

    var blocksTable = new BlocksTable({
        tableBaseUrl: '/cms-slot-block-gui/slot-block/table',
        paramIdCmsSlotTemplate: 'id-cms-slot-template',
        paramIdCmsSlot: 'id-cms-slot',
        blocksTableSelector: '.js-cms-slot-block-table',
        cmsSlotBlocksSelector: '.js-cms-slot-blocks',
        slotBlocksForm: slotBlocksForm,
        viewBlockUrl: '/cms-block-gui/view-block',
    });

    var blocksChoice = new BlocksChoice({
        blocksChoiceFormSelector: '[name=block-choice]',
        blocksTable: blocksTable,
        blocksChoiceAddSelector: '#block-choice_add',
    });

    var slotBlocks = new SlotBlocks({
        slotTableClass: '.js-row-list-of-slots table',
        baseUrl: '/cms-slot-block-gui/slot-block/index',
        blocksTable: blocksTable,
        blocksChoice: blocksChoice,
        slotBlocksForm: slotBlocksForm,
    });

    slotBlocks.init();
});