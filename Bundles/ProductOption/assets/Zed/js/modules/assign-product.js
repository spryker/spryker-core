/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('./main');

var allProductsTable;
var productOptionTable;

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

        productOptionTable.getSelector().removeProductFromSelection(id);
        productOptionTable.updateSelectedProductsLabelCount();
        $('#' + productOptionTable.getCheckBoxNamePrefix() + id).prop('checked', true);
    }

    return false;
}

function ProductSelector() {
    var productSelector = {};
    var selectedProducts = {};
    var idKey = 'id';

    productSelector.addProductToSelection = function (idProduct) {
        selectedProducts[idProduct] = idProduct;
    };

    productSelector.removeProductFromSelection = function (idProduct) {
        delete selectedProducts[idProduct];
    };

    productSelector.isProductSelected = function (idProduct) {
        return selectedProducts.hasOwnProperty(idProduct);
    };

    productSelector.clearAllSelections = function () {
        selectedProducts = {};
    };

    productSelector.addAllToSelection = function (data) {
        for (var i = 0; i < data.length; i++) {
            var id = data[i][idKey];
            selectedProducts[id] = id;
        }
    };

    productSelector.getSelected = function () {
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
        destinationTable: destinationTable,
    };

    var destinationTableProductSelector = new ProductSelector();

    tableHandler.selectAll = function () {
        var nodes = sourceTable.dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', true);

        var sourceTableData = sourceTable.DataTable().rows().data();
        sourceTableData.each(function (cellData, index) {
            tableHandler.addSelectedProduct(cellData[0], cellData[1], cellData[2]);
        });
    };

    tableHandler.deSelectAll = function () {
        var nodes = sourceTable.dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', false);

        var sourceTableData = sourceTable.DataTable().rows().data();
        sourceTableData.each(function (cellData, index) {
            tableHandler.removeSelectedProduct(cellData[0]);
        });
    };

    tableHandler.addSelectedProduct = function (idProduct, sku, name) {
        if (destinationTableProductSelector.isProductSelected(idProduct)) {
            return;
        }
        destinationTableProductSelector.addProductToSelection(idProduct);

        destinationTable
            .dataTable()
            .fnAddData([
                idProduct,
                decodeURIComponent((sku + '').replace(/\+/g, '%20')),
                decodeURIComponent((name + '').replace(/\+/g, '%20')),
                '<div><a data-id="' +
                    idProduct +
                    '" data-action="' +
                    tableHandler.getAction() +
                    '" href="#" class="btn btn-xs remove-item">Remove</a></div>',
            ]);

        $('.remove-item').off('click');
        $('.remove-item').on('click', removeActionHandler);

        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.removeSelectedProduct = function (idProduct) {
        var selectedProductsData = destinationTable.DataTable().rows().data();
        selectedProductsData.each(function (cellData, index) {
            var currentId = cellData[0];

            if (parseInt(currentId) === parseInt(idProduct)) {
                destinationTableProductSelector.removeProductFromSelection(idProduct);
                destinationTable.dataTable().fnDeleteRow(this.row());
                var checkbox = $('#' + tableHandler.getCheckBoxNamePrefix() + idProduct);
                checkbox.prop('checked', false);
            }
        });

        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.getSelector = function () {
        return destinationTableProductSelector;
    };

    tableHandler.updateSelectedProductsLabelCount = function () {
        $('#' + tableHandler.getLabelId()).text(
            labelCaption + ' (' + Object.keys(this.getSelector().getSelected()).length + ')',
        );
        var productIds = Object.keys(this.getSelector().getSelected());
        var s = productIds.join(',');
        var field = $('#' + tableHandler.getFormFieldId());
        field.attr('value', s);
    };

    tableHandler.getCheckBoxNamePrefix = function () {
        return tableHandler.checkBoxNamePrefix;
    };

    tableHandler.getLabelId = function () {
        return tableHandler.labelId;
    };

    tableHandler.getAction = function () {
        return tableHandler.action;
    };

    tableHandler.getLabelCaption = function () {
        return tableHandler.labelCaption;
    };

    tableHandler.getFormFieldId = function () {
        return tableHandler.formFieldId;
    };

    tableHandler.getSourceTable = function () {
        return tableHandler.sourceTable;
    };

    tableHandler.getDestinationTable = function () {
        return tableHandler.destinationTable;
    };

    return tableHandler;
}

$(document).ready(function () {
    var currentTableId = 'products-to-assign';

    allProductsTable = new TableHandler(
        $('#product-table'),
        $('#selectedProductsTable'),
        'all_products_checkbox_',
        'Products to be assigned',
        'products-to-be-assigned',
        'select',
        'product_option_general_products_to_be_assigned',
    );

    productOptionTable = new TableHandler(
        $('#product-option-table'),
        $('#deselectedProductsTable'),
        'product_category_checkbox_',
        'Products to be deassigned',
        'to-be-deassigned',
        'deselect',
        'product_option_general_products_to_be_de_assigned',
    );

    $('#product-table').dataTable({
        destroy: true,
        fnDrawCallback: function (settings) {
            $('.all-products-checkbox').off('change');
            $('.all-products-checkbox').on('change', function () {
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
        },
    });

    productOptionTable.deSelectAll = function () {
        var sourceTableData = productOptionTable.getSourceTable().DataTable().rows().data();
        var nodes = productOptionTable.getSourceTable().dataTable().fnGetNodes();
        $('input[type="checkbox"]', nodes).prop('checked', false);

        sourceTableData.each(function (cellData, index) {
            productOptionTable.addSelectedProduct(cellData[0], cellData[1], cellData[2]);
        });
    };

    productOptionTable.removeSelectedProduct = function (idProduct) {
        var destinationTable = productOptionTable.destinationTable;
        var selectedProductsData = destinationTable.DataTable().rows().data();
        selectedProductsData.each(function (cellData, index) {
            var currentId = cellData[0];

            if (parseInt(currentId) === parseInt(idProduct)) {
                productOptionTable.getSelector().removeProductFromSelection(idProduct);
                destinationTable.dataTable().fnDeleteRow(index);
            }
        });

        productOptionTable.updateSelectedProductsLabelCount();
    };

    productOptionTable.selectAll = function () {
        var nodes = productOptionTable.getSourceTable().dataTable().fnGetNodes();
        var sourceTableData = productOptionTable.getSourceTable().DataTable().rows().data();

        $('input[type="checkbox"]', nodes).prop('checked', true);
        sourceTableData.each(function (cellData, index) {
            productOptionTable.removeSelectedProduct(cellData[0]);
        });
    };

    $('#product-option-table').dataTable({
        destroy: true,
        fnDrawCallback: function (settings) {
            $('.product_category_checkbox').off('change');
            $('.product_category_checkbox').on('change', function () {
                var $checkbox = $(this);
                var info = $.parseJSON($checkbox.attr('data-info'));

                if ($checkbox.prop('checked')) {
                    productOptionTable.removeSelectedProduct(info.id);
                    allProductsTable.removeSelectedProduct(info.id);
                } else {
                    productOptionTable.addSelectedProduct(info.id, info.sku, info.name);
                }
            });

            for (var i = 0; i < settings.json.data.length; i++) {
                var product = settings.json.data[i];
                var idProduct = parseInt(product[0]);

                var selector = productOptionTable.getSelector();
                if (selector.isProductSelected(idProduct)) {
                    $('#' + productOptionTable.getCheckBoxNamePrefix() + idProduct).prop('checked', false);
                }
            }
        },
    });

    $('#product-selectors .btn').each(function (index, element) {
        $(element).on('click', function (event) {
            $('#product-selectors .btn').removeClass('active');

            $('#products-assignment > div').each(function (index, containerElement) {
                $(containerElement).hide();
            });

            var targetElement = $(event.target);
            targetElement.addClass('active');

            var dataElementId = targetElement.attr('id');
            currentTableId = dataElementId;

            if (dataElementId == 'products-to-assign' || dataElementId === 'assigned') {
                $('#select-all-btn').show();
            } else {
                $('#select-all-btn').hide();
            }

            var productContainer = $('#products-assignment').find('[data-products="' + dataElementId + '"]');
            $(productContainer).show();
        });
    });

    $('#select-all').on('click', function () {
        if (currentTableId == 'products-to-assign') {
            allProductsTable.selectAll();
        } else {
            productOptionTable.selectAll();
        }
        return false;
    });

    $('#deselect-all').on('click', function () {
        if (currentTableId == 'products-to-assign') {
            allProductsTable.deSelectAll();
        } else {
            productOptionTable.deSelectAll();
        }
        return false;
    });
});
