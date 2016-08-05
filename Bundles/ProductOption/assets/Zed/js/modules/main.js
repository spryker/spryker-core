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
});
