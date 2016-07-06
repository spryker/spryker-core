/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

$(document).ready(function() {
    $('.spryker-form-select2combobox').select2({
        tags: true
    });

    //$('.attribute_metadata_value').prop('disabled', false);

    $('.attribute_metadata_checkbox').each(function() {
        var $item = $(this);
        var $input = $item
            .parents('.attribute_metadata_row')
            .find('.attribute_metadata_value');

        if (!$item.prop('disabled')) {
            $input.prop('disabled', !$item.prop('checked'));
        }
    });

    $('.attribute_metadata_checkbox')
        .off('click')
        .on('click', function() {
            var $item = $(this);
            $item
                .parents('.attribute_metadata_row')
                .find('.attribute_metadata_value')
                .prop('disabled', !$item.prop('checked'));
        });

});