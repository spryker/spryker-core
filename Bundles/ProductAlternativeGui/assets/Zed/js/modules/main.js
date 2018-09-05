/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('ZedGui');

$(document).ready(function() {
    jQuery('#product_concrete_form_edit_alternative_products').select2({
      ajax: {
        url: '/product-alternative-gui/suggest',
        delay: 250,
        dataType: 'json',
        cache: true
      },
      minimumInputLength: 3
    });
});
