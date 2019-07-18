/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

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
     * Toggle predefined value translation
     */
    $('.translate_values_checkbox').on('change', function() {
        $('.translate_values_checkbox').prop('checked', $(this).prop('checked'));
        toggleValueTranslations();
    });

    toggleValueTranslations();

    var isSuperCheckBox = $('#attributeForm_is_super');

    isSuperCheckBox
        .off('click')
        .on('click', function() {
            var checkboxIsChecked = isSuperCheckBox.prop('checked');
            var allowInputCheckbox = $('#attributeForm_allow_input');
            allowInputCheckbox.prop('checked', false);
            allowInputCheckbox.prop('disabled', checkboxIsChecked);
        });

});
