/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

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

function TableHandler(sourceTable, destinationTable, labelCaption, labelId, formFieldId, onRemoveCallback) {
    var tableHandler = {
        labelId: labelId,
        labelCaption: labelCaption,
        formFieldId: formFieldId,
        sourceTable: sourceTable,
        destinationTable: destinationTable
    };

    var destinationTableProductSelector = new ProductSelector();

    tableHandler.toggleSelection = function() {
        $('input[type="checkbox"]', sourceTable).each(function(index, checkboxNode) {
            var $checkbox = $(checkboxNode);
            $checkbox.prop('checked', !$checkbox.prop('checked'));
            $checkbox.trigger('change');
        });

        return false;
    };

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
        idProduct = parseInt(idProduct, 10);
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
                '<div><a data-id="' + idProduct + '" href="#" class="btn btn-xs remove-item">Remove</a></div>'
            ])
            .draw();

        $('.remove-item').off('click');
        $('.remove-item').on('click', onRemoveCallback);

        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.removeSelectedProduct = function(idProduct) {
        idProduct = parseInt(idProduct, 10);

        destinationTable.DataTable().rows().every(function(rowIndex, tableLoop, rowLoop) {
            if (!this.data()) {
                return;
            }

            var rowProductId = parseInt(this.data()[0], 10);
            if (idProduct !== rowProductId) {
                return;
            }

            destinationTableProductSelector.removeProductFromSelection(idProduct);

            this.remove();

            var $checkbox = $('input[value="' + idProduct + '"]', sourceTable);
            $checkbox.prop('checked', false);
        });

        destinationTable.DataTable().draw();
        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.getSelector = function() {
        return destinationTableProductSelector;
    };

    tableHandler.updateSelectedProductsLabelCount = function() {
        $(tableHandler.getLabelId()).text(labelCaption + ' (' + Object.keys(this.getSelector().getSelected()).length + ')');
        var productIds = Object.keys(this.getSelector().getSelected());
        var s = productIds.join(',');
        var field = $('#' + tableHandler.getFormFieldId());
        field.attr('value', s);
    };

    tableHandler.getLabelId = function() {
        return tableHandler.labelId;
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

function create(sourceTableSelector, destinationTableSelector, checkboxSelector, labelCaption, labelId, formFieldId, onRemoveCallback)
{
    $(destinationTableSelector).DataTable({destroy: true});

    var tableHandler = new TableHandler(
        $(sourceTableSelector),
        $(destinationTableSelector),
        labelCaption,
        labelId,
        formFieldId,
        onRemoveCallback
    );

    $(sourceTableSelector).DataTable().on('draw', function(event, settings) {
        $(checkboxSelector, $(sourceTableSelector)).off('change');
        $(checkboxSelector, $(sourceTableSelector)).on('change', function() {
            var $checkbox = $(this);
            var info = $.parseJSON($checkbox.attr('data-info'));

            if ($checkbox.prop('checked')) {
                tableHandler.addSelectedProduct(info.id, info.sku, info.name);
            } else {
                tableHandler.removeSelectedProduct(info.id);
            }
        });

        for (var i = 0; i < settings.json.data.length; i++) {
            var product = settings.json.data[i];
            var idProduct = parseInt(product[1], 10);

            var selector = tableHandler.getSelector();
            if (selector.isProductSelected(idProduct)) {
                $('input[value="' + idProduct + '"]', $(sourceTableSelector)).prop('checked', true);
            }
        }
    });

    return tableHandler;
}

module.exports = {
    create: create,
    TableHandler: TableHandler
};
