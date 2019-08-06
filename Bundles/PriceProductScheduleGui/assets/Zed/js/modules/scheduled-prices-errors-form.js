/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    $('#scheduled-prices-errors-form').DataTable();

    var $store = $('#price_product_schedule_idStore');
    console.log($store);
    // When sport gets selected ...
    $store.change(function() {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected sport value.
        var data = {};
        data[$store.attr('name')] = $store.value;
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                // Replace current position field ...
                $('#price_product_schedule_idCurrency').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#price_product_schedule_idCurrency')
                );
                // Position field now displays the appropriate positions.
            }
        });
    });
});
