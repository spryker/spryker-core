/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('./main');

$(document).on('ready', function () {
    $('.super-attribute-checkbox-input').on('change', handleSuperAttributeCheckboxChange);
    $('#sku-autogenerate-checkbox-input').on('change', handleSkuAutogenerateCheckboxChange);
});

function handleSuperAttributeCheckboxChange(e) {
    var $target = $(e.target),
        checkboxState = $target.is(':checked'),
        $inputsGroup = $target.closest('.super-attribute-inputs-group'),
        $textInput = $($inputsGroup.find('.super-attribute-text-input')[0]),
        $dropdownInput = $($inputsGroup.find('.super-attribute-dropdown-input')[0]);

    $dropdownInput.prop('disabled', checkboxState);
    checkboxState ?
        $textInput.removeClass('hidden') :
        $textInput.addClass('hidden');
}

function handleSkuAutogenerateCheckboxChange(e) {
    $('#sku-input').prop('disabled', $(e.target).is(':checked'));
}