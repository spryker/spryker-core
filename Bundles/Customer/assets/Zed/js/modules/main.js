/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {

    var dateOfBirth = $('#customer_date_of_birth');

    dateOfBirth.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        maxDate: dateOfBirth.val(),
        defaultData: 0,
        onClose: function (selectedDate) {
            dateOfBirth.datepicker('option', 'minDate', selectedDate);
        }
    });
});
