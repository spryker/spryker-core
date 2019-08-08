/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../../sass/main.scss');

var OptionValueFormHandler = require('./product-option-value-form-handler');

$(document).ready(function() {

    new OptionValueFormHandler();

    $('#create-product-option-button').on('click', function(e) {
        e.preventDefault();

        $(this)
            .prop('disabled', true)
            .addClass('disabled');

        $('#product_option_general').submit();
    });

    $('.ibox-content').each(function(index, content) {
        var hasErrors = $(content).find('.has-error, .alert-danger');
        if (hasErrors.length == 0) {
            return;
        }

        $(content).parent().addClass('error');

    });
});
