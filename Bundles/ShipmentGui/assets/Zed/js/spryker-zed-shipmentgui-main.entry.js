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

    var trigger = document.getElementById('shipment_form_create_id_shipping_address');

    function toggleForm() {
        var target = $('#shipment_form_create_shipping_address');
        var selectedOption = trigger.selectedIndex;

        if (!!selectedOption) {
            target.hide();
        } else {
            target.show();
        }
    }

    trigger.addEventListener('change', toggleForm, false);
});
