/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var selectedProductsAPI = {
    selectedProductsData: [],
    removeBtnSelector: '.js-remove-item',
    removeBtnTemplate: '<a href="#" class="js-remove-item btn-xs">Remove</a>',
    counterSelector: '.js-counter',
    counterTemplate: '<span class="js-counter"></span>',
    
    /**
     * Init all table adding functionality.
     * @param {string} productTable - Current table with products.
     * @param {string} selectedProductsTable - Table where should product be added.
     * @param {string} checkboxSelector - Checkbox selector.
     * @param {string} counterLabelSelector - Tabs label where will be added count of select products.
     * @param {string} inputWithSelectedProducts - In this input will putted all selected product ids.
     */
    init: function(productTable, selectedProductsTable, checkboxSelector, counterLabelSelector, inputWithSelectedProducts) {
        this.$productTable = $(productTable);
        this.$selectedProductsTable = $(selectedProductsTable);
        this.$counterLabel = $(counterLabelSelector);
        this.$inputWithSelectedProducts = $(inputWithSelectedProducts);
        this.checkboxSelector = checkboxSelector;

        this.drawProductsTable();
        this.addRemoveButtonClickHandler();
        this.addCounterToLabel();
    },

    selectProductsOnLoad: function(initialSelectedProductsData) {
        var productTable = this.$productTable.DataTable(),
            productTableData = productTable.data(),
            data = initialSelectedProductsData.replace(/&quot;/g, '"');

        if (data != undefined) {
            var parsedData = JSON.parse(data);

            for (var i = 0; i < parsedData.length; i++) {
                parsedData[i].push('');
                this.addRow(parsedData[i]);
            }
        }
    },

    /**
     * Draw method of DataTable. Fires every time table rerender.
     */
    drawProductsTable: function() {
        var self = this,
            productTableData = self.$productTable.DataTable();

        productTableData.on('draw', function(event, settings) {
            self.updateCheckboxes();
            self.mapEventsToCheckboxes(productTableData, $(self.checkboxSelector));

            if (self.$inputWithSelectedProducts) {
                var initialSelectedProductsData;

                initialSelectedProductsData = self.$inputWithSelectedProducts.data('fake-table');

                if (initialSelectedProductsData) {
                    self.selectProductsOnLoad(initialSelectedProductsData);
                    self.$inputWithSelectedProducts.data('fake-table', '');
                }
            }
        });
    },

    /**
     * Add change event for all checkboxes checkbox. Fires every time, when product table redraws.
     * @param {object} productTableData - DataTable options ( get by $(element).DataTable() ).
     * @param {collectionNodes} checkboxes - Collection of all checkboxes in Product Table.
     */
    mapEventsToCheckboxes: function(productTableData, checkboxes) {
        var self = this;

        checkboxes.off('change');
        checkboxes.on('change', function () {
            var rowIndex = checkboxes.index($(this)),
                rowData = productTableData.data()[rowIndex],
                id = rowData[0];

            if($(this).is(':checked')) {
                return self.addRow(rowData);
            }

            return self.removeRow(id);
        });
    },

    /**
     * Check for selected products in product table.
     */
    updateCheckboxes: function() {
        var productTable = this.$productTable.DataTable(),
            productTableData = productTable.data();

        for(var i = 0; i < productTableData.length; i++) {
            var productItemData = productTableData[i],
                productItemId = productItemData[0],
                checkBox = $(productTable.row(i).node()).find('[type="checkbox"]');

            checkBox.prop('checked', false);

            this.findSelectedProductsInTable(checkBox, productItemId);
        }
    },

    /**
     * Check for selected products in product table.
     * @param {object} checkBox - Jquery object with checkbox.
     * @param {number} productItemId - Id if product row.
     */
    findSelectedProductsInTable: function(checkBox,productItemId) {
        for(var j = 0; j < this.selectedProductsData.length; j++) {
            var selectedProductId = this.selectedProductsData[j][0];

            if(productItemId == selectedProductId) {
                checkBox.prop('checked', true);
                break;
            }
        }
    },

    /**
     * Update counter.
     */
    updateCounter: function() {
        var counterText = '';

        if(this.selectedProductsData.length) {
            counterText = ' ('+this.selectedProductsData.length+')';
        }

        $(this.counterSelector).html(counterText);
    },

    /**
     * Update selected products input value.
     * @param {number} id - Selected product id.
     */
    updateSelectedProductsInputValue: function() {
        var inputValue = this.selectedProductsData.reduce(function(concat, current, index) {
            return index ? concat + ',' + current[0] : current[0];
        }, '');

        this.$inputWithSelectedProducts.val(inputValue);
    },

    /**
     * Add selected product to array with all selected items.
     * @param {array} rowData - Array of all data selected product.
     */
    addRow: function(rowData) {
        var productItem = rowData.slice();
        productItem[rowData.length - 1] = this.removeBtnTemplate.replace('#', productItem[0]);
        this.selectedProductsData.push(productItem);
        this.renderSelectedItemsTable(productItem);
    },

    /**
     * Remove row from array with all selected items.
     * @param {number} id - Products id which should be deleted.
     */
    removeRow: function(id) {
        var self = this;

        this.selectedProductsData.every(function(elem,index) {
            if(elem[0] == id) {
                self.selectedProductsData.splice(index,1);
                return false;
            }
            return true;
        });

        self.renderSelectedItemsTable();
    },

    /**
     * Add event for remove button to remove row from array with all selected items.
     */
    addRemoveButtonClickHandler: function() {
        var self = this,
            selectedTable = this.$selectedProductsTable;

        selectedTable.on('click', this.removeBtnSelector, function (e) {
            e.preventDefault();

            var id = $(e.target).attr('href');

            self.removeRow(id);
            self.updateCheckboxes();
        });
    },

    /**
     * Add counter template on init.
     */
    addCounterToLabel: function() {
        this.$counterLabel.append(this.counterTemplate);
    },

    /**
     * Redraw table with selected items.
     */
    renderSelectedItemsTable: function() {
        this.$selectedProductsTable
        .DataTable()
        .clear()
        .rows
        .add(this.selectedProductsData).draw();

        this.updateCounter();
        this.updateSelectedProductsInputValue();
        this.updateCheckboxes();
    }
}

$(document).ready(function() {
    selectedProductsAPI.init(
        '#product-table',
        '#selectedProductsTable',
        '.all-products-checkbox',
        'a[href="#tab-content-assign"]',
        '#productList_productListProductConcreteRelation_assigned_products'
    );
});