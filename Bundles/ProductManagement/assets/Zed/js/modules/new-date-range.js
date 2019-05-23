/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {

    var $fromDate = $('.js-from-date');
    var $toDate = $('.js-to-date');

    $fromDate.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        maxDate: $toDate.val(),
        defaultData: 0,
        onClose: function(selectedDate) {
            $toDate.datepicker('option', 'minDate', selectedDate);
        }
    });

    $toDate.datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: $fromDate.val(),
        onClose: function(selectedDate) {
            $fromDate.datepicker('option', 'maxDate', selectedDate);
        }
    });

});
