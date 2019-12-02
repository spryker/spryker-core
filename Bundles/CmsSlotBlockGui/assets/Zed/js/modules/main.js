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
    var blocksChoiceFormSelector = '[name=block-choice]';
    var cmsSlotBlocksSelector = '.js-cms-slot-blocks';

    var slotBlocksForm = new SlotBlocksForm({
        cmsSlotBlocksSelector: cmsSlotBlocksSelector,
        slotBlockFormItemClass: '.js-cms-slot-block-form-item',
        slotBlockFormItemIdPrefix: '#js-cms-slot-block-form-item-',
        slotBlockFormWrapperId: '#js-cms-slot-block-form-inner-wrapper',
    });

    var blocksTable = new BlocksTable({
        tableBaseUrl: '/cms-slot-block-gui/slot-block/table',
        blocksTableSelector: '.js-cms-slot-block-table',
        cmsSlotBlocksSelector: cmsSlotBlocksSelector,
        cmsSlotBlocksOverlaySelector: '.js-cms-slot-blocks__overlay',
        cmsSlotBlocksOverlayTogglerClass: 'cms-slot-blocks__overlay--hidden',
        slotBlocksForm: slotBlocksForm,
        viewBlockUrl: '/cms-block-gui/view-block',
        blocksChoiceFormSelector: blocksChoiceFormSelector,
    });

    var blocksChoice = new BlocksChoice({
        blocksChoiceFormSelector: blocksChoiceFormSelector,
        blocksTable: blocksTable,
        blocksChoiceAddSelector: '#block-choice_add',
    });

    var slotBlocks = new SlotBlocks({
        slotSelector: '.js-row-list-of-slots',
        slotTableSelector: '.js-row-list-of-slots table',
        blockContainerSelector: '.js-row-list-of-blocks-container',
        cmsSlotBlockContentProviderSelector: '#cms-slot-block-content-provider',
        baseUrl: '/cms-slot-block-gui/slot-block/index',
        paramIdCmsSlotTemplate: 'id-cms-slot-template',
        paramIdCmsSlot: 'id-cms-slot',
        blocksTable: blocksTable,
        blocksChoice: blocksChoice,
        slotBlocksForm: slotBlocksForm,
        contentProviderAttribute: 'data-content-provider',
    });

    global.CmsSlotGui_SlotTable.dataTableInitCallback = function() {
        slotBlocks.init();
    };
});
