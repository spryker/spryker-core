/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../scss/main.scss');

var shippingAddressToggle = function() {
    if ($('input[name="addresses[shippingAddress][id_customer_address]"]:last').is(':checked')) {
        $('#manualOrderShippingAddress .__toggler-target').show();
    } else {
        $('#manualOrderShippingAddress .__toggler-target').hide();
    }
};

var billingAddressToggle = function() {
    if ($('input[name="addresses[billingAddress][id_customer_address]"]:last').is(':checked')) {
        $('#manualOrderBillingAddress .__toggler-target').show();
    } else {
        $('#manualOrderBillingAddress .__toggler-target').hide();
    }
};

var billingAddressSelectionToggle = function() {
    if ($('#addresses_billingSameAsShipping').is(':checked')) {
        $('#manualOrderBillingAddress .__toggler-target-selection').hide();
    } else {
        $('#manualOrderBillingAddress .__toggler-target-selection').show();
    }
};

$(document).ready( function () {
    var productCount = 3;

    $('#add-another-product').click(function() {
        var productList = $('#product-fields-list');

        var skuWidget = productList.attr('data-prototype-sku');
        var quantityWidget = productList.attr('data-prototype-quantity');

        skuWidget = skuWidget.replace(/__name__/g, productCount);
        quantityWidget = quantityWidget.replace(/__name__/g, productCount);
        productCount++;

        // create a new list element and add it to the list
        var skuLine = $('<td></td>').html(skuWidget);
        var quantityLine = $('<td></td>').html(quantityWidget);
        var addLine = '<tr><td>' + $(skuLine).html() + '</td><td>' + $(quantityLine).html() + '</td></tr>';

        $(addLine).appendTo($('#product-fields-list'));

        return false;
    });

    shippingAddressToggle();
    $('input[name="addresses[shippingAddress][id_customer_address]"]').click(shippingAddressToggle);

    billingAddressToggle();
    $('input[name="addresses[billingAddress][id_customer_address]"]').click(billingAddressToggle);

    billingAddressSelectionToggle();
    $('#addresses_billingSameAsShipping').click(billingAddressSelectionToggle);


    $('form.ManualOrderEntryForm input').on('keyup keypress', function(e) {
        if (e.which === 13) {
            var btn = $('form.ManualOrderEntryForm input.submitBtn');
            if (btn.length) {
                btn.click();
            } else {
                $('form.ManualOrderEntryForm input.nextStepBtn').click();
            }

        }
    });
});
