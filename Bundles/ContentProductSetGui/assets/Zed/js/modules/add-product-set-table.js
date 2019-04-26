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
    clearAllFieldsSelector,
    orderButtonSelector
) {
    this.tablesWrapperSelector = tablesWrapperSelector;
    this.hiddenInputsWrapperSelector = hiddenInputsWrapperSelector;
    this.assignedTables = $(assignedTableSelector);
    this.productsTables = $(productTableSelector);
    this.clearAllFieldsButton = $(clearAllFieldsSelector).removeClass(clearAllFieldsSelector.substring(1));
    this.addProductButtonSelector = addProductButtonSelector;
    this.removeProductButtonSelector = removeProductButtonSelector;
    this.orderButtonSelector = orderButtonSelector;

    this.mapEvents = function() {
        this.productsTables.on('click', this.addProductButtonSelector, this.addProductButtonHandler.bind(this));
        this.assignedTables.on('click', this.removeProductButtonSelector, this.removeProductButtonHandler.bind(this));
        this.assignedTables.on('click', this.orderButtonSelector, this.changeOrderButtonHandler.bind(this));
        this.clearAllFieldsButton.on('click', this.clearAllFieldsButtonsHandler.bind(this));
    };

    this.addProductButtonHandler = function(event) {
        var clickInfo = this.getClickInfo(event);
        var indexOfActiveTable = this.productsTables.index(clickInfo.clickedTable);

        if (this.isProductAdded(clickInfo.clickedTable, clickInfo.productId)) {
            return;
        }

        this.addProduct(clickInfo.clickedTable, clickInfo.productId, indexOfActiveTable);
    };

    this.removeProductButtonHandler = function(event) {
        var clickInfo = this.getClickInfo(event);
        var tableRow = clickInfo.button.parents('tr');

        this.removeHiddenInput(clickInfo.clickedTable, clickInfo.productId);
        this.removeProduct(clickInfo.clickedTable, tableRow, clickInfo.productId);
    }

    this.changeOrderButtonHandler = function(event) {
        var clickInfo = this.getClickInfo(event);

        this.changeOrder(clickInfo.button, clickInfo.clickedTable);
    }

    this.clearAllFieldsButtonsHandler = function(event) {
        event.preventDefault();

        var button = $(event.currentTarget);
        var indexOfclickedButton = this.clearAllFieldsButton.index(button);
        var assignedTable = this.getCurrentAssignedTable(indexOfclickedButton);

        this.removeAllHiddenInputs(assignedTable);
        assignedTable.dataTable().api().clear().draw();
    };

    this.removeProductButtonClick = function(button, assignedTable) {
        var productId = button.data('id');
        var tableRow = button.parents('tr');

        this.removeHiddenInput(assignedTable, productId);
        this.removeProduct(assignedTable, tableRow, productId);
    }

    this.changeOrder = function(button, assignedTable) {
        var productId = button.data('id');
        var direction = button.data('direction');
        var tableApi = assignedTable.dataTable().api();
        var tableData = tableApi.data().toArray();
        var indexOfClickedRow = tableApi.row(button.parents('tr')).index();
        var removedFromDataArray = tableData.splice(indexOfClickedRow, 1)[0];
        var hiddenInput = this.getHiddenInputForMoving(assignedTable, productId);

        if (direction === 'up') {
            hiddenInput.insertBefore(hiddenInput.prev());
            tableData.splice(indexOfClickedRow - 1, 0, removedFromDataArray);
        }

        if (direction === 'down') {
            hiddenInput.insertAfter(hiddenInput.next());
            tableData.splice(indexOfClickedRow + 1, 0, removedFromDataArray);
        }

        tableApi.rows().remove();
        tableApi.rows.add(tableData).draw();
    }

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
        var hiddenInputsWrapper = this.getHiddenInputsWrapper(tablesWrapper);
        var hiddenInputTemplate = this.getHiddenInputTemplate(tablesWrapper);
        var hiddenInput = $(this.replaceHiddenInputId(hiddenInputTemplate, hiddenInputsWrapper));

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

    this.replaceHiddenInputId = function(hiddenInputTemplate, hiddenInputsWrapper) {
        var hiddenInputsLength = hiddenInputsWrapper.find('input').length;

        return hiddenInputTemplate.replace(/__name__/g, hiddenInputsLength);
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
        rowData.push(this.getDeleteButtonsTemplate(productId));

        return rowData;
    };

    this.getDeleteButtonsTemplate = function(productId) {
        var buttons = $($(this.tablesWrapperSelector).data('delete-button'));
        var buttonsTemplate = '';

        buttons.each(function() {
            var button = $(this);
            if (button.is('button')) {
                buttonsTemplate += button.attr('data-id', productId)[0].outerHTML + ' ';
            }
        });

        return buttonsTemplate;
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

    this.getHiddenInputForMoving = function(assignedTable, productId) {
        var hiddenInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));
        var hiddenInput = this.getHiddenInput(hiddenInputsWrapper, productId);

        return hiddenInput;
    }

    this.getTablesWrapper = function(productTable) {
        return productTable.parents(this.tablesWrapperSelector)
    }

    this.getClickInfo = function(event) {
        return {
            button: $(event.currentTarget),
            productId: $(event.currentTarget).data('id'),
            clickedTable: $(event.delegateTarget)
        }
    }

    this.mapEvents()
};

$(document).ready(function () {
    new ProductListContentItem(
        '.id-product-set-fields',
        '.product-set-selected-table',
        '.product-set-view-table',
        '.js-selected-products-wrapper',
        '.js-add-product-set',
        '.js-delete-product-set',
        '.clear-fields',
        '.js-reorder-product-set'
    );
});
