/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    $('#shipment_form_create_requested_delivery_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: 0,
        defaultData: 0
    });

    var triggerCreate = document.getElementById('shipment_form_create_id_customer_address');

    function toggleCreateForm() {
        var target = $('#shipment_form_create_shipping_address');
        var selectedOption = triggerCreate.selectedIndex;

        if (!selectedOption) {
            target.show();
            return;
        }

        target.hide();
    }

    triggerCreate.addEventListener('change', toggleCreateForm, false);
});
