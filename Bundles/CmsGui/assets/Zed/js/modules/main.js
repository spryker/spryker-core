/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var CmsGlossaryAutocomplete = require('./cms-glossary-autocomplete');
require('../../sass/main.scss');
require('../../img/cms-loader.gif');

$(document).ready( function () {

    var validFrom = $('#cms_page_validFrom');
    var validTo = $('#cms_page_validTo');

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

    $("input[id$='translationKey']").each(function(index, element){
        new CmsGlossaryAutocomplete({
            autocompleteElement: $(element)
        });
    });
});
