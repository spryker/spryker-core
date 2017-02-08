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

    $('#cms_page_validFrom').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        defaultData: 0,
        onClose: function(selectedDate) {
            $('#cms_page_validFrom').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#cms_page_validTo').datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function(selectedDate) {
            $('#cms_page_validFrom').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $("input[id$='translationKey']").each(function(index, element){
        var options = {
            autocompleteElement: $(element)
        };
        new CmsGlossaryAutocomplete(options)
    });
});
