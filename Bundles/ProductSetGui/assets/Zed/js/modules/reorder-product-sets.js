/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    var $productSetWeightsField = $('#reorder_product_sets_form_product_set_weights');
    var productSetWeights = getProductSetWeights();

    $('#product-set-reorder-table').DataTable().on('draw', function(event, settings) {
        $('.product_set_weight')
            .off('change')
            .on('change', onProductSetWeightChange);

        setProductSetWeightFieldsOnTableDraw(settings);
    });

    /**
     * @returns {Object}
     */
    function getProductSetWeights() {
        if ($productSetWeightsField.attr('value')) {
            return $.parseJSON($productSetWeightsField.attr('value'));
        }

        return {};
    }

    /**
     * @returns {void}
     */
    function onProductSetWeightChange() {
        var $input = $(this);
        var id = $.parseJSON($input.attr('data-id'));

        productSetWeights[id] = $input.val();
        $productSetWeightsField.attr('value', JSON.stringify(productSetWeights));
        console.log(productSetWeights);
    }

    /**
     * @returns {void}
     */
    function setProductSetWeightFieldsOnTableDraw(settings) {
        for (var i = 0; i < settings.json.data.length; i++) {
            var product = settings.json.data[i];
            var idProduct = parseInt(product[0]);

            if (productSetWeights.hasOwnProperty(idProduct)) {
                $('#product_set_weight_' + idProduct).val(parseInt(productSetWeights[idProduct]) || 0);
            }
        }
    }
});
