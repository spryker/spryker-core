/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

require('./main');

$(document).ready(function() {
    //$('.nodes li[data-node="child"]').toggle($(this).prop('checked'));

    $('#form_delete_children')
        .off('click')
        .on('click', function() {
            //$('.nodes li[data-node="child"]').toggle($(this).prop('checked'));
            $('.spryker-form-select2combobox').val(null).trigger('change');
        });

    $('#form_fk_parent_category_node')
        .on('select2:select', function() {
            $('#form_delete_children').prop('checked', false);
        });

    $('#delete_confirm')
        .off('click')
        .on('click', function() {
            $('#submit_delete').prop('disabled', !$(this).prop('checked'));
        });
});
