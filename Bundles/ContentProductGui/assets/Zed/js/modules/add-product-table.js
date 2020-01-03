/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ProductListContentItem = function(
    tablesWrapperSelector,
    assignedTableSelector,
    productTableSelector,
    integerInputsWrapperSelector,
    addProductButtonSelector,
    removeProductButtonSelector,
    clearAllFieldsSelector,
    orderButtonSelector,
    navigationTabLinkSelector,
    tabsContentSelector
) {
    this.tablesWrapperSelector = tablesWrapperSelector;
    this.integerInputsWrapperSelector = integerInputsWrapperSelector;
    this.assignedTables = $(assignedTableSelector);
    this.productsTables = $(productTableSelector);
    this.clearAllFieldsButton = $(clearAllFieldsSelector).removeClass(clearAllFieldsSelector.substring(1));
    this.addProductButtonSelector = addProductButtonSelector;
    this.removeProductButtonSelector = removeProductButtonSelector;
    this.orderButtonSelector = orderButtonSelector;
    this.navigationTabLinks = $(navigationTabLinkSelector);
    this.tabsContent = $(tabsContentSelector);

    this.mapEvents = function() {
        this.productsTables.on('click', this.addProductButtonSelector, this.addProductButtonHandler.bind(this));
        this.assignedTables.on('click', this.removeProductButtonSelector, this.removeProductButtonHandler.bind(this));
        this.assignedTables.on('click', this.orderButtonSelector, this.changeOrderButtonHandler.bind(this));
        this.clearAllFieldsButton.on('click', this.clearAllFieldsButtonsHandler.bind(this));
        this.navigationTabLinks.on('click', this.resizeTableColumn.bind(this));
    };

    this.resizeTableColumn = function(event) {
        var tabId = event.target.getAttribute('href');
        var self = this;
        this.tabsContent.each(function(index, item) {
            var currentTabId = item.getAttribute('id');
            var isOpenTab = tabId.substring(1) === currentTabId;

            if (isOpenTab) {
                $(item).show();
                $(tabId).find(self.assignedTables).DataTable().columns.adjust().draw();
                $(tabId).find(self.productsTables).DataTable().columns.adjust().draw();
            } else {
                $(item).hide();
            }
        });

    }

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
        var integerInput = this.getHiddenInputForMoving(assignedTable, productId);

        if (direction === 'up') {
            integerInput.insertBefore(integerInput.prev());
            tableData.splice(indexOfClickedRow - 1, 0, removedFromDataArray);
        }

        if (direction === 'down') {
            integerInput.insertAfter(integerInput.next());
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
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(productTable));
        var integerInput = this.getHiddenInput(integerInputsWrapper, productId);

        return integerInput.length;
    }

    this.addHiddenInput = function(tablesWrapper, productId, indexOfActiveTable) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(tablesWrapper);
        var integerInputTemplate = this.getHiddenInputTemplate(tablesWrapper);
        var integerInput = $(this.replaceIntegerInputId(integerInputTemplate, integerInputsWrapper));

        integerInput.attr('value', productId);
        integerInputsWrapper.append(integerInput);
    };

    this.removeHiddenInput = function(assignedTable, productId) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));
        var integerInput = this.getHiddenInput(integerInputsWrapper, productId);

        integerInput.remove();
    };

    this.removeAllHiddenInputs = function(assignedTable) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));

        integerInputsWrapper.empty();
    };

    this.replaceIntegerInputId = function(integerInputTemplate, integerInputsWrapper) {
        var indexes = [0];
        integerInputsWrapper.find('input').each(function (index, element) {
            indexes.push(element.name.match(/\d+/g).pop());
        });
        var integerInputsLength = Math.max.apply(null, indexes);


        return integerInputTemplate.replace(/__name__/g, integerInputsLength + 1);
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
            if (button.is('a')) {
                buttonsTemplate += button.attr('data-id', productId)[0].outerHTML + ' ';
            }
        });

        return buttonsTemplate;
    }

    this.getHiddenInputTemplate = function(tablesWrapper) {
        return tablesWrapper.data('prototype');
    };

    this.getHiddenInputsWrapper = function(tablesWrapper) {
        return tablesWrapper.find(this.integerInputsWrapperSelector);
    }

    this.getHiddenInput = function(wrapper, productId) {
        return wrapper.find('input[value="' + productId + '"]');
    }

    this.getHiddenInputForMoving = function(assignedTable, productId) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));
        var integerInput = this.getHiddenInput(integerInputsWrapper, productId);

        return integerInput;
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
        '.id-product-abstract-fields',
        '.product-abstract-selected-table',
        '.product-abstract-view-table',
        '.js-selected-products-wrapper',
        '.js-add-product-abstract',
        '.js-delete-product-abstract',
        '.clear-fields',
        '.js-reorder-product-abstract',
        '.nav-tabs a',
        '.tab-content .tab-pane'
    );
});
