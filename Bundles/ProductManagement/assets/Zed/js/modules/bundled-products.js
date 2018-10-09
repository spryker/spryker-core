/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready(function() {

    var numberOfAssignedProducts = $('#assigned-product-template').data('value-count');
    var assignProductFormHTML = $('#assigned-product-template').data('prototype');
    var bundledItemsToBeRemoved = [];

    $('#bundled-product-table').dataTable({
        destroy: true,
        scrollX: 'auto',
        autoWidth: false,
        fnDrawCallback: function(settings) {
            $('.product_assign_checkbox').off('change');
            $('.product_assign_checkbox').on('change', function() {
                var $checkbox = $(this);
                var info = $.parseJSON($checkbox.attr('data-info'));

                if ($checkbox.prop('checked')) {
                    numberOfAssignedProducts++;

                    var newBundledProductFormHTML = assignProductFormHTML.replace(/__name__/g, numberOfAssignedProducts);
                    var $newBundledProductForm = $(jQuery.parseHTML(newBundledProductFormHTML)[1]);

                    var $idProductConcreteFormField = $newBundledProductForm.find("input[id$='id_product_concrete']");
                    $idProductConcreteFormField.val(info['id_product']);

                    var $skuElement = $newBundledProductForm.find("input[id$='sku']");
                    $skuElement.val(info['sku']);

                    var bundledFormId = $newBundledProductForm.attr('id');
                    $checkbox.attr('data-related-form-id', bundledFormId);

                    var $removeButton = $newBundledProductForm.find('.btn-remove');

                    $removeButton.on('click', function() {
                        $('#' + bundledFormId).remove();
                        $checkbox.prop('checked', false);
                        numberOfAssignedProducts--;
                    });

                    $('#bundled-products').append($newBundledProductForm);

                } else {

                    var idProductConcrete = info['id_product'];
                    var $idProductConcreteElement = $('#bundled-products').find('input[id$=id_product_concrete][value=' + idProductConcrete + ']');

                    bundledItemsToBeRemoved.push(idProductConcrete);
                    $idProductConcreteElement.parent().parent().remove();
                    numberOfAssignedProducts--;
                }
            });
        },
        fnInitComplete: function(oSettings, json) {

            $('#bundled-products .row').each(function(index, element) {
                var $removeButton = $(element).find('.btn-remove');

                var idProductConcrete = $(element).find("input[id$='id_product_concrete']").val();
                $('#product_assign_checkbox_' + idProductConcrete).prop('checked', true);

                $removeButton.on('click', function() {

                    var form = $(element);

                    $('#bundled-product-table').DataTable().rows().data().each(function(cell) {
                        var cellIdProductConcrete = cell[1];

                        if (parseInt(idProductConcrete) === parseInt(cellIdProductConcrete)) {
                            var $checkbox = $('#product_assign_checkbox_' + cellIdProductConcrete);
                            $checkbox.prop('checked', false);

                            var bundledFormId = form.attr('id');
                            $checkbox.attr('data-related-form-id', bundledFormId);

                            numberOfAssignedProducts--;
                        }
                    });

                    bundledItemsToBeRemoved.push(idProductConcrete);
                    $(element).remove();

                });
            });
        }
    });

    $('form[name=product_concrete_form_edit]').on('submit', function() {
        var elementsToRemove = '';
        var size = bundledItemsToBeRemoved.length;
        bundledItemsToBeRemoved.forEach(function(element, index) {
            elementsToRemove += element;

            if (index + 1 < size) {
                elementsToRemove += ',';
            }
        });
        $('#product_concrete_form_edit_product_bundles_to_be_removed').val(elementsToRemove);
    });

});
