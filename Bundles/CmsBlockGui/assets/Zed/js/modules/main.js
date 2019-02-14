/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready( function () {

    var validFrom = $('#cms_block_validFrom');
    var validTo = $('#cms_block_validTo');

    validFrom.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        maxDate: validTo.val(),
        defaultData: 0,
        onClose: function(selectedDate) {
            validTo.datepicker('option', 'minDate', selectedDate);
        }
    });

    validTo.datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: validFrom.val(),
        onClose: function(selectedDate) {
            validFrom.datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('[name=cms_block_glossary]').on('submit', function() {
        var self = $(this);

        self.find('.html-editor').each(function (index, element) {

            var editor = $(element);

            if (editor.summernote('codeview.isActivated')) {
                editor.summernote('codeview.deactivate');
            }

            if (editor.summernote('isEmpty')) {
                editor.val(null);
            }
        });
    });
});
