/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready( function () {
    $('#cms_block_id_product_abstracts').select2({
        ajax: {
            url: '/cms-block-product-connector/product-autocomplete/',
            dataType: 'json',
            delay: 500,
            cache: true,
            processResults: function (data) {
                data.results = data.results.map(function(item) {
                    return {
                        id: item.sku,
                        text: item.name + ' (SKU: ' + item.sku + ')'
                    };
                });

                return data;
            }
        },
        minimumInputLength: 3
    });
});
