/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    let customerStoreFormGroup = $('#customer_store_name_form_group');
    let customerSendPasswordTokenCheckbox = $('#customer_send_password_token');
    let customerStoreNameDropDown = $('#customer_store_name');

    // Handle elements status on page load. If backed validation fails and we get
    // redirected back with send customer password checkbox selected,
    // the store dropdown and its validation error are still shown.
    if (customerSendPasswordTokenCheckbox.is(':checked')) {
        enableCustomerStoreSelection();
    } else {
        disableCustomerStoreSelection();
    }

    customerSendPasswordTokenCheckbox.change(function () {
        if (this.checked) {
            enableCustomerStoreSelection();
        } else {
            disableCustomerStoreSelection();
        }
    });

    function enableCustomerStoreSelection() {
        customerStoreNameDropDown.attr('disabled', false);
        customerStoreFormGroup.show();
    }

    function disableCustomerStoreSelection() {
        customerStoreNameDropDown.attr('disabled', true);
        customerStoreFormGroup.hide();
    }
});
