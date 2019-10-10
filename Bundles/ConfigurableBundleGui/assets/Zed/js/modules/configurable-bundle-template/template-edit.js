/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var slotProductsTable = require('./slot-products-table');
var $selectedForm = null;

$(document).ready(function () {
    slotProductsTable.init();
    $('#slot-table-wrapper').on('submit', 'form[name="delete_form"]', slotDeleteButtonHandler)
    $('#slot-delete-confirmation-modal #btn-confirm').on('click', deleteConfirmButtonHandler)
    $('#slot-delete-confirmation-modal #btn-cancel').on('click', deleteCancelButtonHandler)
});

/**
 * @param event
 *
 * @return {void}
 */
function slotDeleteButtonHandler(event) {
    event.preventDefault();
    $('#slot-delete-confirmation-modal').modal('show');
    $selectedForm = event.target;
}

/**
 * @return {void}
 */
function deleteConfirmButtonHandler() {
    if ($selectedForm) {
        $selectedForm.submit();
    }
}

/**
 * @return {void}
 */
function deleteCancelButtonHandler() {
    if ($selectedForm) {
        $($selectedForm).find('button[type="submit"]')
            .prop('disabled', false)
            .removeClass('disabled');
    }
}
