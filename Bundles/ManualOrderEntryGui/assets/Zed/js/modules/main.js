/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

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
        // var addLine = $('<tr></tr>').html(html);

        $(addLine).appendTo($('#product-fields-list'));

        return false;
    });

});
