/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    var triggerEdit = document.getElementById('shipment_form_edit_id_customer_address');
    var triggerCreate = document.getElementById('shipment_form_create_id_customer_address');

    function initDatepicker(target) {
        $(target).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 3,
            minDate: 0,
            defaultData: 0
        });
    }

    function toggleEditForm(target, trigger) {
        var selectedOptionValue = trigger.options[trigger.selectedIndex].value;

        if (!selectedOptionValue) {
            $(target).show();

            return;
        }

        $(target).hide();
    }

    initDatepicker('#shipment_form_edit_requested_delivery_date');
    initDatepicker('#shipment_form_create_requested_delivery_date');

    if (triggerEdit) {
        toggleEditForm('#shipment_form_edit_shipping_address', triggerEdit);

        triggerEdit.addEventListener('change', function () {
            toggleEditForm('#shipment_form_edit_shipping_address', triggerEdit);
        });
    }

    if (triggerCreate) {
        toggleEditForm('#shipment_form_create_shipping_address', triggerCreate);

        triggerCreate.addEventListener('change', function () {
            toggleEditForm('#shipment_form_create_shipping_address', triggerCreate)
        });
    }
});
