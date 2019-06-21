/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ItemListContentItem = function(options)
{
    $.extend(this, options);

    this.$assignedTables = $(this.assignedTableSelector);
    this.$itemsTables = $(this.itemTableSelector);
    this.$clearAllFieldsButton = $(this.clearAllFieldsSelector).removeClass(this.clearAllFieldsSelector.substring(1));
    this.$navigationTabLinks = $(this.navigationTabLinkSelector);
    this.$tabsContent = $(this.tabsContentSelector);

    this.mapEvents = function() {
        this.$itemsTables.on('click', this.addItemButtonSelector, this.addItemButtonHandler.bind(this));
        this.$assignedTables.on('click', this.removeItemButtonSelector, this.removeItemButtonHandler.bind(this));
        this.$assignedTables.on('click', this.orderButtonSelector, this.changeOrderButtonHandler.bind(this));
        this.$clearAllFieldsButton.on('click', this.$clearAllFieldsButtonsHandler.bind(this));
        this.$navigationTabLinks.on('click', this.resizeTableColumn.bind(this));
    };

    this.resizeTableColumn = function(event) {
        var tabId = event.target.getAttribute('href');
        var self = this;
        this.$tabsContent.each(function(index, item) {
            var currentTabId = item.getAttribute('id');
            var isOpenTab = tabId.substring(1) === currentTabId;

            if (!isOpenTab) {
                $(item).hide();
                return;
            }

            $(item).show();
            $(tabId).find(self.$assignedTables).DataTable().columns.adjust().draw();
            $(tabId).find(self.$itemsTables).DataTable().columns.adjust().draw();
        });

    };

    this.addItemButtonHandler = function(event) {
        var clickInfo = this.getClickInfo(event);
        var indexOfActiveTable = this.$itemsTables.index(clickInfo.clickedTable);

        if (this.isItemAdded(clickInfo.clickedTable, clickInfo.itemId)) {
            return;
        }

        this.addItem(clickInfo.clickedTable, clickInfo.itemId, indexOfActiveTable);
    };

    this.removeItemButtonHandler = function(event) {
        var clickInfo = this.getClickInfo(event);
        var tableRow = clickInfo.button.parents('tr');

        this.removeHiddenInput(clickInfo.clickedTable, clickInfo.itemId);
        this.removeItem(clickInfo.clickedTable, tableRow, clickInfo.itemId);
    };

    this.changeOrderButtonHandler = function(event) {
        var clickInfo = this.getClickInfo(event);

        this.changeOrder(clickInfo.button, clickInfo.clickedTable);
    };

    this.$clearAllFieldsButtonsHandler = function(event) {
        event.preventDefault();

        var button = $(event.currentTarget);
        var indexOfclickedButton = this.$clearAllFieldsButton.index(button);
        var assignedTable = this.getCurrentAssignedTable(indexOfclickedButton);

        this.removeAllHiddenInputs(assignedTable);
        assignedTable.dataTable().api().clear().draw();
    };

    this.removeItemButtonClick = function(button, assignedTable) {
        var itemId = button.data('id');
        var tableRow = button.parents('tr');

        this.removeHiddenInput(assignedTable, itemId);
        this.removeItem(assignedTable, tableRow, itemId);
    };

    this.changeOrder = function(button, assignedTable) {
        var itemId = button.data('id');
        var direction = button.data('direction');
        var tableApi = assignedTable.dataTable().api();
        var tableData = tableApi.data().toArray();
        var indexOfClickedRow = tableApi.row(button.parents('tr')).index();
        var removedFromDataArray = tableData.splice(indexOfClickedRow, 1)[0];
        var integerInput = this.getHiddenInputForMoving(assignedTable, itemId);

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
    };

    this.addItem = function(itemTable, itemId, indexOfActiveTable) {
        var rowData = this.getRowData(itemTable, itemId);
        var assignedTable = this.getCurrentAssignedTable(indexOfActiveTable);
        var tablesWrapper = this.getTablesWrapper(assignedTable);

        this.addHiddenInput(tablesWrapper, itemId, indexOfActiveTable);
        assignedTable.dataTable().api().row.add(rowData).draw();
    };

    this.removeItem = function(assignedTable, tableRow, itemId) {
        assignedTable.dataTable().api().row(tableRow).remove().draw();
    };

    this.isItemAdded = function(itemTable, itemId) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(itemTable));
        var integerInput = this.getHiddenInput(integerInputsWrapper, itemId);

        return integerInput.length;
    };

    this.addHiddenInput = function(tablesWrapper, itemId, indexOfActiveTable) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(tablesWrapper);
        var integerInputTemplate = this.getHiddenInputTemplate(tablesWrapper);
        var integerInput = $(this.replaceIntegerInputId(integerInputTemplate, integerInputsWrapper));

        integerInput.attr('value', itemId);
        integerInputsWrapper.append(integerInput);
    };

    this.removeHiddenInput = function(assignedTable, itemId) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));
        var integerInput = this.getHiddenInput(integerInputsWrapper, itemId);

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
    };

    this.getCurrentAssignedTable = function(indexOfActiveTable) {
        return this.$assignedTables.eq(indexOfActiveTable);
    };

    this.getRowData = function(itemTable, itemId) {
        var tableData = itemTable.dataTable().api().data().toArray();
        var rowData = tableData.find(function(item) {
            if (item[0] === Number(itemId)) {
                return item;
            }
        });

        rowData.splice(-1,1);
        rowData.push(this.getDeleteButtonsTemplate(itemId));

        return rowData;
    };

    this.getDeleteButtonsTemplate = function(itemId) {
        var buttons = $($(this.tablesWrapperSelector).data('delete-button'));
        var buttonsTemplate = '';

        buttons.each(function() {
            var button = $(this);
            if (button.is('a')) {
                buttonsTemplate += button.attr('data-id', itemId)[0].outerHTML + ' ';
            }
        });

        return buttonsTemplate;
    };

    this.getHiddenInputTemplate = function(tablesWrapper) {
        return tablesWrapper.data('prototype');
    };

    this.getHiddenInputsWrapper = function(tablesWrapper) {
        return tablesWrapper.find(this.integerInputsWrapperSelector);
    };

    this.getHiddenInput = function(wrapper, itemId) {
        return wrapper.find('input[value="' + itemId + '"]');
    };

    this.getHiddenInputForMoving = function(assignedTable, itemId) {
        var integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));

        return this.getHiddenInput(integerInputsWrapper, itemId);
    };

    this.getTablesWrapper = function(itemTable) {
        return itemTable.parents(this.tablesWrapperSelector)
    };

    this.getClickInfo = function(event) {
        return {
            button: $(event.currentTarget),
            itemId: $(event.currentTarget).data('id'),
            clickedTable: $(event.delegateTarget)
        }
    };

    this.mapEvents()
};

$(document).ready(function () {
    new ItemListContentItem({
        'tablesWrapperSelector': '.id-item-fields',
        'assignedTableSelector': '.item-list-selected-table',
        'itemTableSelector': '.item-list-view-table',
        'integerInputsWrapperSelector': '.js-selected-items-wrapper',
        'addItemButtonSelector': '.js-add-item',
        'removeItemButtonSelector': '.js-delete-item',
        'clearAllFieldsSelector': '.clear-fields',
        'orderButtonSelector': '.js-reorder-item',
        'navigationTabLinkSelector': '.nav-tabs a',
        'tabsContentSelector': '.tab-content .tab-pane'
    });
});
