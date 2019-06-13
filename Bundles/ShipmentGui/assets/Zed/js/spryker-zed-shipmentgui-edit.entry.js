/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    $('#shipment_form_edit_requested_delivery_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: 0,
        defaultData: 0
    });

    var triggerEdit = document.getElementById('shipment_form_edit_id_shipping_address');

    function toggleEditForm() {
        var target = $('#shipment_form_edit_shipping_address');
        var selectedOption = triggerEdit.selectedIndex;

        if (!selectedOption) {
            target.show();
        } else {
            target.hide();
        }
    }

    toggleEditForm();

    triggerEdit.addEventListener('change', toggleEditForm, false);
});
