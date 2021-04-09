/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

'use strict';

require('../../scss/main.scss');
var FormAction = require('./form-action');
var ItemsCounter = require('./items-counter');
var ItemsToggler = require('./items-toggler');
var ReasonMessageToggler = require('./reason-message-toggler');

$(document).ready(function () {
    new FormAction({
        tableSelector: '.js-return-items-table',
        itemSelector: '.js-check-item',
        actionButtonSelector: '.js-return-bulk-trigger-buttons button',
    });

    new ItemsCounter({
        tableSelector: '.js-return-items-table',
        allItemsSelector: '.js-check-all-items',
        itemSelector: '.js-check-item',
        checkedItemSelector: '.js-check-item:checked',
        counterWrapperSelector: '.js-item-counter-wrapper',
        counterSelector: '.js-item-counter',
    });

    new ItemsToggler({
        tableSelector: '.js-return-items-table',
        allItemsSelector: '.js-check-all-items',
        itemSelector: '.js-check-item',
        checkedItemSelector: '.js-check-item:checked',
        submitButtonSelector: '.js-create-return-submit',
    });

    new ReasonMessageToggler({
        selectSelector: '.js-select-reason',
        toggleValue: 'custom_reason',
    });
});
