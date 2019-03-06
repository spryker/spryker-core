/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

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

    var originalText = $('#version-diff .has-original .original');
    originalText.each(function(index, element){
        var targets = $('#version-diff .has-diff .original');
        if (typeof targets[index] !== 'undefined') {
            var targetsDiff = $('#version-diff .has-diff .diff');
            targets[index].innerText = element.innerText;

            if (element.innerText !== targetsDiff[index].innerText) {
                $(targetsDiff[index]).css('background-color', '#fbd6c4')
            }
        }
    });

    $('[name=cms_glossary]').on('submit', function() {
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
