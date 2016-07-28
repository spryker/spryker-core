/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

$(document).ready(function() {

    var valueList = $('#option-value-list');
    var valueCount = parseInt(valueList.data('value-count'));

    if (valueCount > 0) {
        $('.form-product-option-row').each(function(index, element) {
            addOptionFormActions($(element));
        });
    }

    $('#add-another-option').click(function(event) {
        event.preventDefault();

        var newOptionFormHTML = valueList.data('prototype');

        valueCount++;

        newOptionFormHTML = newOptionFormHTML.replace(/__name__/g, valueCount);
        var newOptionForm = $(jQuery.parseHTML(newOptionFormHTML));

        addOptionFormActions(newOptionForm);

        valueList.append(newOptionForm);

    });

    function addOptionFormActions(newOptionForm)
    {
        newOptionForm.find('.btn-remove').on('click', function(event) {
            $(event.target).parent().parent().remove();
        });

        var skuElement = newOptionForm.find("input[id$='sku']");
        newOptionForm.find("input[id$='value']").on('keyup', function(event) {

            var nameElementValue = $(event.target).val();
            var formattedValue = nameElementValue.toLowerCase().replace(/\s/g, '_');

            skuElement.val('OP_' + formattedValue);
        });
    }
});
