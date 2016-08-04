/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

var Navigation = require('./navigation');
var OptionValueFormHandler = require('./product-option-value-form-handler');

$(document).ready(function() {

    new Navigation();
    new OptionValueFormHandler();

    $('#create-product-option-button').on('click', function(event) {
        event.preventDefault();

        $('#product_option_general').submit();
    });

    if ($('#create-product-option').length) {
        $('#add-another-option').trigger('click');
    }

    $('.copy-language').on('click', function() {
        event.preventDefault();

        var translationFormContainer = $(event.target).parent().parent();

        var translationKey = translationFormContainer.find("input[id$='key']").val();
        var translationVal = translationFormContainer.find("input[id$='name']").val();

        $('.translation-tabs').find('.form-product-option-translation-row').each(function(index, element) {
            if ($(element).find("input[id$='key']").val() == translationKey) {
                $(element).find("input[id$='name']").val(translationVal);
            }
        });
    });
});
