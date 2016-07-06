/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('./main');

$(document).ready(function() {
    $('.attribute_metadata_checkbox')
        .off('click')
        .on('click', function() {
            $('.attribute_metadata_value').prop('disabled', !$(this).prop('checked'));
        });
});
