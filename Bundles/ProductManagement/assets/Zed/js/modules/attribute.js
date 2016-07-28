/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('ZedGui');

function toggleValueTranslations()
{
    if ($('.translate_values_checkbox').is(':checked')) {
        $('.value_translation_container').show();
    } else {
        $('.value_translation_container').hide();
    }
}

$(document).ready(function() {

    $('.spryker-form-select2combobox').select2({
        tags: true
    });

    /**
     * Copy translations to other languages on click
     */
    $('.copy-to-other-languages').on('click', function() {
        var sourceTab = $(this).data('sourceTab');
        var sourceInputClass = $(this).data('sourceInputClass');

        $('#' + sourceTab).find('.' + sourceInputClass).each(function(i, input) {
            var valueToCopy = $(input).val();

            $('.tab-translation')
                .not('#' + sourceTab)
                .find('.' + sourceInputClass)
                .eq(i)
                .val(valueToCopy);
        });
    });

    /**
     * Toggle predefined value translation
     */
    $('.translate_values_checkbox').on('change', function() {
        $('.translate_values_checkbox').prop('checked', $(this).prop('checked'));
        toggleValueTranslations();
    });

    toggleValueTranslations();

});
