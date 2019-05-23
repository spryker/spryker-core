/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var productPosition;
var allProductsTable;
var productAbstractSetTable;

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

        productAbstractSetTable.getSelector().removeProductFromSelection(id);
        productAbstractSetTable.updateSelectedProductsLabelCount();
        $('#' + productAbstractSetTable.getCheckBoxNamePrefix() + id).prop('checked', true);
    }

    return false;
}

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
        sourceTableData.each(function(data, index) {
            tableHandler.addSelectedProduct(data[0], data);
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

    tableHandler.addSelectedProduct = function(id, data) {
        if (destinationTableProductSelector.isProductSelected(id)) {
            return;
        }
        destinationTableProductSelector.addProductToSelection(id);

        data[data.length - 1] = '<div><a data-id="' + id + '" data-action="' + tableHandler.getAction() + '" href="#" class="btn btn-xs remove-item">Remove</a></div>';

        destinationTable.DataTable()
            .row
            .add(data)
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

    tableHandler.getRowDataByElement = function(elementInRow) {
        var tr = $(elementInRow).parents('tr');
        var table = tr.parents('table').DataTable();

        return table.row(tr).data();
    };

    return tableHandler;
}

$(document).ready(function() {
    var rawProductPosition = $('#product_set_form_products_form_product_position').attr('value');

    if (rawProductPosition) {
        productPosition = $.parseJSON(rawProductPosition);
    }

    $('#selectedProductsTable').DataTable({destroy: true});
    $('#deselectedProductsTable').DataTable({destroy: true});

    productAbstractSetTable = new TableHandler(
        $('#product-abstract-set-table'),
        $('#deselectedProductsTable'),
        'product_checkbox_',
        'Products to be deassigned',
        'deassigned-tab-label',
        'deselect',
        'product_set_form_products_form_deassign_id_product_abstracts'
    );

    allProductsTable = new TableHandler(
        $('#product-table'),
        $('#selectedProductsTable'),
        'all_products_checkbox_',
        'Products to be assigned',
        'assigned-tab-label',
        'select',
        'product_set_form_products_form_assign_id_product_abstracts'
    );

    $('#product-table').DataTable().on('draw', function(event, settings) {
        $('.all-products-checkbox').off('change');
        $('.all-products-checkbox').on('change', function() {
            var $checkbox = $(this);
            var id = $.parseJSON($checkbox.attr('data-id'));
            var data = allProductsTable.getRowDataByElement(this);

            if ($checkbox.prop('checked')) {
                allProductsTable.addSelectedProduct(id, data);
            } else {
                allProductsTable.removeSelectedProduct(id);
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

    productAbstractSetTable.deSelectAll = function() {
        var sourceTableData = productAbstractSetTable.getSourceTable().DataTable().rows().data();
        var nodes = productAbstractSetTable.getSourceTable().dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', false);

        sourceTableData.each(function(data, index) {
            productAbstractSetTable.addSelectedProduct(data[0], data);
        });
    };

    productAbstractSetTable.removeSelectedProduct = function(idProduct) {
        var destinationTable = productAbstractSetTable.destinationTable;
        var selectedProductsData = destinationTable.DataTable().rows().data();
        selectedProductsData.each(function(cellData, index) {
            var currentId = cellData[0];

            if (parseInt(currentId) === parseInt(idProduct)) {
                productAbstractSetTable.getSelector().removeProductFromSelection(idProduct);
                destinationTable.dataTable().fnDeleteRow(index);
                var checkbox = $('#' + productAbstractSetTable.getCheckBoxNamePrefix() + idProduct);
                checkbox.prop('checked', true);
            }
        });

        productAbstractSetTable.updateSelectedProductsLabelCount();
    };

    $('#product-abstract-set-table').DataTable().on('draw', function(event, settings) {
        $('.product_checkbox').off('change');
        $('.product_checkbox').on('change', function() {
            var $checkbox = $(this);
            var id = $.parseJSON($checkbox.attr('data-id'));
            var data = productAbstractSetTable.getRowDataByElement(this);

            if ($checkbox.prop('checked')) {
                productAbstractSetTable.removeSelectedProduct(id);
                allProductsTable.removeSelectedProduct(id);
            } else {
                productAbstractSetTable.addSelectedProduct(id, data);
            }
        });

        $('.product_position').off('change');
        $('.product_position').on('change', function() {
            var $input = $(this);
            var id = $.parseJSON($input.attr('data-id'));
            productPosition[id] = $input.val();
            console.log(productPosition);
            $('#product_set_form_products_form_product_position').attr('value', JSON.stringify(productPosition));
        });

        for (var i = 0; i < settings.json.data.length; i++) {
            var product = settings.json.data[i];
            var idProduct = parseInt(product[0]);

            var selector = productAbstractSetTable.getSelector();
            if (selector.isProductSelected(idProduct)) {
                $('#' + productAbstractSetTable.getCheckBoxNamePrefix() + idProduct).prop('checked', false);
            }

            if (productPosition.hasOwnProperty(idProduct)) {
                $('#product_position_' + idProduct).val(parseInt(productPosition[idProduct]) || 0);
            }
        }
    });

    $('.prcat-select-all a').on('click', function() {
        allProductsTable.selectAll();
        return false;
    });

    $('.prcat-deselect-all a').on('click', function() {
        productAbstractSetTable.deSelectAll();
        return false;
    });
});
