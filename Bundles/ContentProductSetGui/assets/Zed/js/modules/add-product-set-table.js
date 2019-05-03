/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const ContentProductSetGui = function(options)
{
    $.extend(this, options);

    this.$assignedTables = $(this.assignedTableSelector);
    this.$productSetsTables = $(this.productSetsTableSelector);
    this.$clearAllFieldsButton = $(this.clearAllFieldsSelector).removeClass(this.clearAllFieldsSelector.substring(1));
    this.$navigationTabLinks = $(this.navigationTabLinkSelector);
    this.$tabsContent = $(this.tabsContentSelector);

    this.mapEvents = function() {
        this.$productSetsTables.on('click', this.addProductSetButtonSelector, this.addProductSetButtonHandler.bind(this));
        this.$assignedTables.on('click', this.removeProductSetButtonSelector, this.removeProductSetButtonHandler.bind(this));
        this.$clearAllFieldsButton.on('click', this.clearAllFieldsButtonsHandler.bind(this));
        this.$navigationTabLinks.on('click', this.resizeTableColumn.bind(this));
    };

    this.resizeTableColumn = function(event) {
        let tabId = event.target.getAttribute('href');
        let self = this;
        this.$tabsContent.each(function(index, item) {
            let currentTabId = item.getAttribute('id');
            let isOpenTab = tabId.substring(1) === currentTabId;

            if (isOpenTab) {
                $(item).show();
                $(tabId).find(self.$assignedTables).DataTable().columns.adjust().draw();
                $(tabId).find(self.$productSetsTables).DataTable().columns.adjust().draw();
            } else {
                $(item).hide();
            }
        });

    };

    this.addProductSetButtonHandler = function(event) {
        let clickInfo = this.getClickInfo(event);
        let indexOfActiveTable = this.$productSetsTables.index(clickInfo.clickedTable);

        this.addProductSet(clickInfo.clickedTable, clickInfo.idProductSet, indexOfActiveTable);
    };

    this.removeProductSetButtonHandler = function(event) {
        let clickInfo = this.getClickInfo(event);
        let tableRow = clickInfo.button.parents('tr');

        this.clearHiddenInput(clickInfo.clickedTable);
        this.removeProduct(clickInfo.clickedTable, tableRow);
    };

    this.clearAllFieldsButtonsHandler = function(event) {
        event.preventDefault();

        let button = $(event.currentTarget);
        let indexOfclickedButton = this.$clearAllFieldsButton.index(button);
        let assignedTable = this.getCurrentAssignedTable(indexOfclickedButton);

        this.clearHiddenInput(assignedTable);
        assignedTable.dataTable().api().clear().draw();
    };

    this.addProductSet = function(productTable, idProductSet, indexOfActiveTable) {
        let rowData = this.getRowData(productTable, idProductSet);
        let assignedTable = this.getCurrentAssignedTable(indexOfActiveTable);
        let tablesWrapper = this.getTablesWrapper(assignedTable);

        this.setHiddenInput(tablesWrapper, idProductSet);
        assignedTable.dataTable().api().clear();
        assignedTable.dataTable().api().row.add(rowData).draw();
    };

    this.removeProduct = function(assignedTable, tableRow) {
        assignedTable.dataTable().api().row(tableRow).remove().draw();
    };

    this.setHiddenInput = function(tablesWrapper, idProductSet) {
        let integerInputsWrapper = this.getHiddenInputsWrapper(tablesWrapper);
        let integerInput = this.getHiddenInput(integerInputsWrapper);

        integerInput.attr('value', idProductSet);
    };

    this.clearHiddenInput = function(assignedTable) {
        let integerInputsWrapper = this.getHiddenInputsWrapper(this.getTablesWrapper(assignedTable));
        let integerInput = this.getHiddenInput(integerInputsWrapper);

        integerInput.attr('value', null);
    };

    this.getCurrentAssignedTable = function(indexOfActiveTable) {
        return this.$assignedTables.eq(indexOfActiveTable);
    };

    this.getRowData = function(productTable, idProductSet) {
        let tableData = productTable.dataTable().api().data().toArray();
        let rowData = tableData.find(function(item) {
            if (item[0] === Number(idProductSet)) {
                return item;
            }
        });

        rowData.splice(-1,1);
        rowData.push(this.getDeleteButtonsTemplate(idProductSet));

        return rowData;
    };

    this.getDeleteButtonsTemplate = function(idProductSet) {
        let buttons = $($(this.tablesWrapperSelector).data('delete-button'));
        let buttonsTemplate = '';

        buttons.each(function() {
            let button = $(this);
            if (button.is('button')) {
                buttonsTemplate += button.attr('data-id', idProductSet)[0].outerHTML + ' ';
            }
        });

        return buttonsTemplate;
    };

    this.getHiddenInputsWrapper = function(tablesWrapper) {
        return tablesWrapper.find(this.integerInputsWrapperSelector);
    };

    this.getHiddenInput = function(wrapper) {
        return wrapper.find('input');
    };

    this.getTablesWrapper = function(productSetTable) {
        return productSetTable.parents(this.tablesWrapperSelector)
    };

    this.getClickInfo = function(event) {
        return {
            button: $(event.currentTarget),
            idProductSet: $(event.currentTarget).data('id'),
            clickedTable: $(event.delegateTarget)
        }
    };

    this.mapEvents()
};

$(document).ready(function () {
    new ContentProductSetGui({
        'tablesWrapperSelector': '.id-product-set-fields',
        'assignedTableSelector': '.product-set-selected-table',
        'productSetsTableSelector': '.product-set-view-table',
        'integerInputsWrapperSelector': '.js-selected-product-sets-wrapper',
        'addProductSetButtonSelector': '.js-add-product-set',
        'removeProductSetButtonSelector': '.js-delete-product-set',
        'clearAllFieldsSelector': '.clear-fields',
        'navigationTabLinkSelector': '.nav-tabs a',
        'tabsContentSelector': '.tab-content .tab-pane'
    });
});
