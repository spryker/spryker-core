/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ProductListContentItem = function(
    tablesWrapperSelector,
    assignedTableSelector,
    productTableSelector,
    hiddenInputsWrapperSelector,
    addProductButtonSelector,
    removeProductButtonSelector,
    clearAllFieldsSelector
) {
    this.tablesWrapperSelector = tablesWrapperSelector;
    this.hiddenInputsWrapperSelector = hiddenInputsWrapperSelector;
    this.assignedTables = $(assignedTableSelector);
    this.productsTables = $(productTableSelector);
    this.clearAllFieldsButton = $(clearAllFieldsSelector);
    this.addProductButtonSelector = addProductButtonSelector;
    this.removeProductButtonSelector = removeProductButtonSelector;

    this.mapEvents = function() {
        this.productsTables.on('click', this.addProductButtonSelector, this.addProductButtonHandler.bind(this));
        this.assignedTables.on('click', this.removeProductButtonSelector, this.removeProductButtonHandler.bind(this));
        this.clearAllFieldsButton.on('click', this.clearAllFieldsButtonsHandler.bind(this));
    };

    this.addProductButtonHandler = function(event) {
        var button = $(event.currentTarget);
        var productId = button.data('id');
        var productTable = $(event.delegateTarget);
        var indexOfActiveTable = this.productsTables.index(productTable);

        if (this.isProductAdded(productTable, productId)) {
            return;
        }

        this.addProduct(productTable, productId, indexOfActiveTable);
    };

    this.removeProductButtonHandler = function(event) {
        var button = $(event.currentTarget);
        var productId = button.data('id');
        var assignedTable = $(event.delegateTarget);
        var tableRow = button.parents('tr');

        this.removeHiddenInput(assignedTable, productId);
        this.removeProduct(assignedTable, tableRow, productId);
    }

    this.clearAllFieldsButtonsHandler = function(event) {
        event.preventDefault();

        var button = $(event.currentTarget);
        var indexOfclickedButton = this.clearAllFieldsButton.index(button);
        var assignedTable = this.getCurrentAssignedTable(indexOfclickedButton);

        this.removeAllHiddenInputs(assignedTable);
        assignedTable.dataTable().api().clear().draw();
    };

    this.addProduct = function(productTable, productId, indexOfActiveTable) {
        var rowData = this.getRowData(productTable, productId);
        var assignedTable = this.getCurrentAssignedTable(indexOfActiveTable);
        var tablesWrapper = this.getTablesWrapper(assignedTable);

        this.addHiddenInput(tablesWrapper, productId, indexOfActiveTable);
        assignedTable.dataTable().api().row.add(rowData).draw();
    };

    this.removeProduct = function(assignedTable, tableRow, productId) {
        assignedTable.dataTable().api().row(tableRow).remove().draw();
    };

    this.isProductAdded = function(productTable, productId) {
        var hiddenInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(productTable));
        var hiddenInput = this.getHiddenInput(hiddenInputsWrapper, productId);

        return hiddenInput.length;
    }

    this.addHiddenInput = function(tablesWrapper, productId, indexOfActiveTable) {
        var hiddenInputTemplate = this.getHiddenInputTemplate(tablesWrapper);
        var hiddenInput = $(this.replaceHiddenInputId(hiddenInputTemplate, productId, indexOfActiveTable));
        var hiddenInputsWrapper = this.getHiddenInputsWrapper(tablesWrapper);

        hiddenInput.val(productId);
        hiddenInputsWrapper.append(hiddenInput);
    };

    this.removeHiddenInput = function(assignedTable, productId) {
        var hiddenInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));
        var hiddenInput = this.getHiddenInput(hiddenInputsWrapper, productId);

        hiddenInput.remove();
    };

    this.removeAllHiddenInputs = function(assignedTable) {
        var hiddenInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));

        hiddenInputsWrapper.empty();
    };

    this.replaceHiddenInputId = function(hiddenInputTemplate, productId, indexOfActiveTable) {
        return hiddenInputTemplate.replace(/__name__/g, (indexOfActiveTable + 1) + '_' + productId);
    }

    this.getCurrentAssignedTable = function(indexOfActiveTable) {
        return this.assignedTables.eq(indexOfActiveTable);
    };

    this.getRowData = function(productTable, productId) {
        var tableData = productTable.dataTable().api().data().toArray();
        var rowData = tableData.find(function(item) {
            if (item[0] === Number(productId)) {
                return item;
            }
        });

        rowData.splice(-1,1);
        rowData.push(this.getDeleteButtonTemplate(productId));

        return rowData;
    };

    this.getDeleteButtonTemplate = function(productId) {
        var button = $(this.tablesWrapperSelector).data('delete-button');

        return $(button).attr('data-id', productId)[0].outerHTML;
    }

    this.getHiddenInputTemplate = function(tablesWrapper) {
        return tablesWrapper.data('prototype');
    };

    this.getHiddenInputsWrapper = function(tablesWrapper) {
        return tablesWrapper.find(this.hiddenInputsWrapperSelector);
    }

    this.getHiddenInput = function(wrapper, productId) {
        return wrapper.find('input[value="' + productId + '"]');
    }

    this.getTablesWrapper = function(productTable) {
        return productTable.parents(this.tablesWrapperSelector)
    }

    this.mapEvents()
};

$(document).ready(function () {
    new ProductListContentItem(
        '.id-product-abstract-fields',
        '.product-abstract-selected-table',
        '.product-abstract-view-table',
        '.js-selected-products-wrapper',
        '.js-add-product-abstract',
        '.js-delete-product-abstract',
        '.clear-fields'
    );
});
