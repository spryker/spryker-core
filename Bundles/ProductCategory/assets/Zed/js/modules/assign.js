/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('./main');

var productOrder;
var allProductsTable;
var productCategoryTable;

function removeActionHandler() {
    var $link = $(this);
    var id = $link.data('id');
    var action = $link.data('action');
    var dataTable;

    if (action === 'select') {
        dataTable = $('#selectedProductsTable').DataTable();
        dataTable.row($link.parents('tr')).remove().draw();

        allProductsTable.getSelector().removeProductFromSelection(id);
        allProductsTable.updateSelectedProductsLabelCount();
        $('#' + allProductsTable.getCheckBoxNamePrefix() + id).prop('checked', false);
    } else {
        dataTable = $('#deselectedProductsTable').DataTable();
        dataTable.row($link.parents('tr')).remove().draw();

        productCategoryTable.getSelector().removeProductFromSelection(id);
        productCategoryTable.updateSelectedProductsLabelCount();
        $('#' + productCategoryTable.getCheckBoxNamePrefix() + id).prop('checked', true);
    }

    return false;
}

//TODO fix later, see here: https://spryker.atlassian.net/browse/CD-446
function ProductSelector() {
    var productSelector = {};
    var selectedProducts = {};
    var idKey = 'id';

    productSelector.addProductToSelection = function(idProduct) {
        selectedProducts[idProduct] = idProduct;
    };

    productSelector.removeProductFromSelection = function(idProduct) {
        delete selectedProducts[idProduct];
    };

    productSelector.isProductSelected = function(idProduct) {
        return selectedProducts.hasOwnProperty(idProduct);
    };

    productSelector.clearAllSelections = function() {
        selectedProducts = {};
    };

    productSelector.addAllToSelection = function(data) {
        for (var i = 0; i < data.length; i++) {
            var id = data[i][idKey];
            selectedProducts[id] = id;
        }
    };

    productSelector.getSelected = function() {
        return selectedProducts;
    };

    return productSelector;
}

function TableHandler(sourceTable, destinationTable, checkBoxNamePrefix, labelCaption, labelId, action, formFieldId) {
    var tableHandler = {
        checkBoxNamePrefix: checkBoxNamePrefix,
        labelId: labelId,
        labelCaption: labelCaption,
        action: action,
        formFieldId: formFieldId,
        sourceTable: sourceTable,
        destinationTable: destinationTable
    };

    var destinationTableProductSelector = new ProductSelector();

    tableHandler.selectAll = function() {
        var nodes = sourceTable.dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', true);
        
        var sourceTableData = sourceTable.DataTable().rows().data();
        sourceTableData.each(function(cellData, index) {
            tableHandler.addSelectedProduct(cellData[0], cellData[1], cellData[2]);
        });
    };

    tableHandler.deSelectAll = function() {
        var nodes = sourceTable.dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', false);

        var sourceTableData = sourceTable.DataTable().rows().data();
        sourceTableData.each(function(cellData, index) {
            tableHandler.removeSelectedProduct(cellData[0]);
        });
    };

    tableHandler.addSelectedProduct = function(idProduct, sku, name) {
        if (destinationTableProductSelector.isProductSelected(idProduct)) {
            return;
        }
        destinationTableProductSelector.addProductToSelection(idProduct);

        destinationTable.DataTable()
            .row
            .add([
                idProduct,
                decodeURIComponent((sku + '').replace(/\+/g, '%20')),
                decodeURIComponent((name + '').replace(/\+/g, '%20')),
                '<div><a data-id="' + idProduct + '" data-action="' + tableHandler.getAction() + '" href="#" class="btn btn-xs remove-item">Remove</a></div>'
            ])
            .draw();

        $('.remove-item').off('click');
        $('.remove-item').on('click', removeActionHandler);

        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.removeSelectedProduct = function(idProduct) {
        var selectedProductsData = destinationTable.DataTable().rows().data();
        selectedProductsData.each(function(cellData, index) {
            var currentId = cellData[0];

            if (parseInt(currentId) === parseInt(idProduct)) {
                destinationTableProductSelector.removeProductFromSelection(idProduct);
                destinationTable.dataTable().fnDeleteRow(index);
                var checkbox = $('#' + tableHandler.getCheckBoxNamePrefix() + idProduct);
                checkbox.prop('checked', false);
            }
        });

        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.getSelector = function() {
        return destinationTableProductSelector;
    };

    tableHandler.updateSelectedProductsLabelCount = function() {
        $('#' + tableHandler.getLabelId()).text(labelCaption + ' (' + Object.keys(this.getSelector().getSelected()).length + ')');
        var productIds = Object.keys(this.getSelector().getSelected());
        var s = productIds.join(',');
        var field = $('#' + tableHandler.getFormFieldId());
        field.attr('value', s);
    };

    tableHandler.getCheckBoxNamePrefix = function() {
        return tableHandler.checkBoxNamePrefix;
    };

    tableHandler.getLabelId = function() {
        return tableHandler.labelId;
    };

    tableHandler.getAction = function() {
        return tableHandler.action;
    };

    tableHandler.getLabelCaption = function() {
        return tableHandler.labelCaption;
    };

    tableHandler.getFormFieldId = function() {
        return tableHandler.formFieldId;
    };

    tableHandler.getSourceTable = function() {
        return tableHandler.sourceTable;
    };

    tableHandler.getDestinationTable = function() {
        return tableHandler.destinationTable;
    };

    return tableHandler;
}

$(document).ready(function() {
    productOrder = {};

    $('#selectedProductsTable').DataTable({destroy: true});
    $('#deselectedProductsTable').DataTable({destroy: true});

    productCategoryTable = new TableHandler(
        $('#product-category-table'),
        $('#deselectedProductsTable'),
        'product_category_checkbox_',
        'Products to be deassigned',
        'deassigned-tab-label',
        'deselect',
        'assign_form_products_to_be_de_assigned'
    );

    allProductsTable = new TableHandler(
        $('#product-table'),
        $('#selectedProductsTable'),
        'all_products_checkbox_',
        'Products to be assigned',
        'assigned-tab-label',
        'select',
        'assign_form_products_to_be_assigned'
    );

    $('#product-table').DataTable().on('draw', function(event, settings) {
        $('.all-products-checkbox').off('change');

        $('.all-products-checkbox').on('change', function() {
            var $checkbox = $(this);
            var info = $.parseJSON($checkbox.attr('data-info'));
            
            if ($checkbox.prop('checked')) {
                allProductsTable.addSelectedProduct(info.id, info.sku, info.name);
            } else {
                allProductsTable.removeSelectedProduct(info.id);
            }
        });

        for (var i = 0; i < settings.json.data.length; i++) {
            var product = settings.json.data[i];
            var idProduct = parseInt(product[0]);

            var selector = allProductsTable.getSelector();
            if (selector.isProductSelected(idProduct)) {
                var $checkbox = $('#' + allProductsTable.getCheckBoxNamePrefix() + idProduct);
                $checkbox.prop('checked', true);
            }
        }
    });

    productCategoryTable.deSelectAll = function() {
        var sourceTableData = productCategoryTable.getSourceTable().DataTable().rows().data();
        var nodes = productCategoryTable.getSourceTable().dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', false);

        sourceTableData.each(function(cellData, index) {
            productCategoryTable.addSelectedProduct(cellData[0], cellData[1], cellData[2]);
        });
    };

    productCategoryTable.removeSelectedProduct = function(idProduct) {
        var destinationTable = productCategoryTable.destinationTable;
        var selectedProductsData = destinationTable.DataTable().rows().data();
        selectedProductsData.each(function(cellData, index) {
            var currentId = cellData[0];

            if (parseInt(currentId) === parseInt(idProduct)) {
                productCategoryTable.getSelector().removeProductFromSelection(idProduct);
                destinationTable.dataTable().fnDeleteRow(index);
                var checkbox = $('#' + productCategoryTable.getCheckBoxNamePrefix() + idProduct);
                checkbox.prop('checked', true);
            }
        });

        productCategoryTable.updateSelectedProductsLabelCount();
    };

    $('#product-category-table').DataTable().on('draw', function(event, settings) {
        $('.product_category_checkbox').off('change');
        $('.product_category_checkbox').on('change', function() {
            var $checkbox = $(this);
            var info = $.parseJSON($checkbox.attr('data-info'));

            if ($checkbox.prop('checked')) {
                productCategoryTable.removeSelectedProduct(info.id);
                allProductsTable.removeSelectedProduct(info.id);
            } else {
                productCategoryTable.addSelectedProduct(info.id, info.sku, info.name);
            }
        });

        $('.product_category_order').off('change');
        $('.product_category_order').on('change', function() {
            var $input = $(this);
            var info = $.parseJSON($input.attr('data-info'));
            productOrder[info.id] = $input.val();
            $('#assign_form_product_order').attr('value', JSON.stringify(productOrder));
        });

        for (var i = 0; i < settings.json.data.length; i++) {
            var product = settings.json.data[i];
            var idProduct = parseInt(product[0]);

            var selector = productCategoryTable.getSelector();
            if (selector.isProductSelected(idProduct)) {
                $('#' + productCategoryTable.getCheckBoxNamePrefix() + idProduct).prop('checked', false);
            }

            if (productOrder.hasOwnProperty(idProduct)) {
                $('#product_category_order_' + idProduct).val(parseInt(productOrder[idProduct]) || 0);
            }
        }
    });

    $('.prcat-select-all a').on('click', function() {
        allProductsTable.selectAll();
        return false;
    });

    $('.prcat-deselect-all a').on('click', function() {
        productCategoryTable.deSelectAll();
        return false;
    });
});
