/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectCurrencyTableAPI = function () {
    this.selectedCurrenciesData = [];
    this.removeBtnSelector = '.js-remove-item';
    this.removeBtnTemplate = '<a href="#" class="js-remove-item btn-xs">Remove</a>';
    this.counterSelector = '.js-counter';
    this.counterTemplate = '<span class="js-counter"></span>';
    this.initialDataLoaded = false;

    /**
     * Init all table adding functionality.
     * @param {string} currencyTable - Current table with currencies.
     * @param {string} selectedCurrenciesTable - Table where should currency be added.
     * @param {string} checkboxSelector - Checkbox selector.
     * @param {string} counterLabelSelector - Tabs label where will be added count of select currencies.
     * @param {string} inputWithSelectedCurrencies - In this input will putted all selected currency ids.
     */
    this.init = function (
        currencyTable,
        selectedCurrenciesTable,
        checkboxSelector,
        counterLabelSelector,
        inputWithSelectedCurrencies,
    ) {
        this.$currencyTable = $(currencyTable);
        this.$selectedCurrenciesTable = $(selectedCurrenciesTable);
        this.$counterLabel = $(counterLabelSelector);
        this.$inputWithSelectedCurrencies = $(inputWithSelectedCurrencies);
        this.checkboxSelector = checkboxSelector;
        this.counterSelector = counterLabelSelector + ' ' + this.counterSelector;

        this.drawCurrenciesTable();
        this.addRemoveButtonClickHandler();
        this.addCounterToLabel();
    };

    this.selectCurrenciesOnLoad = function (initialSelectedCurrenciesData) {
        if (this.initialDataLoaded) {
            return;
        }

        var data = initialSelectedCurrenciesData.replace(/&quot;/g, '"').replace(/,/g, '');
        var parsedData = JSON.parse(data);

        for (var i = 0; i < parsedData.length; i++) {
            parsedData[i].push('');
            this.addRow(parsedData[i]);
        }

        this.initialDataLoaded = true;
    };

    /**
     * Draw method of DataTable. Fires every time table rerender.
     */
    this.drawCurrenciesTable = function () {
        var self = this;
        var currencyTableData = self.$currencyTable.DataTable();
        currencyTableData.on('draw', function (event, settings) {
            self.updateCheckboxes();
            self.mapEventsToCheckboxes(
                currencyTableData,
                $('#' + self.$currencyTable.attr('id') + ' ' + self.checkboxSelector),
            );

            if (self.$inputWithSelectedCurrencies && initialSelectedCurrenciesData) {
                var initialSelectedCurrenciesData = self.$inputWithSelectedCurrencies.val();
                self.selectCurrenciesOnLoad(initialSelectedCurrenciesData);
                self.$inputWithSelectedCurrencies.val('');
            }
        });
    };

    /**
     * Add change event for all checkboxes checkbox. Fires every time, when currency table redraws.
     * @param {object} currencyTableData - DataTable options ( get by $(element).DataTable() ).
     * @param {checkboxes} checkboxes - Collection of all checkboxes in Currency Table.
     */
    this.mapEventsToCheckboxes = function (currencyTableData, checkboxes) {
        var self = this;

        checkboxes.off('change');
        checkboxes.on('change', function () {
            var rowIndex = checkboxes.index($(this)),
                rowData = currencyTableData.data()[rowIndex],
                id = rowData[0];

            if ($(this).is(':checked')) {
                return self.addRow(rowData);
            }

            return self.removeRow(id);
        });
    };

    /**
     * Check for selected currencies in currency table.
     */
    this.updateCheckboxes = function () {
        var currencyTable = this.$currencyTable.DataTable(),
            currencyTableData = currencyTable.data();

        for (var i = 0; i < currencyTableData.length; i++) {
            var currencyItemData = currencyTableData[i],
                currencyItemId = currencyItemData[0],
                checkBox = $(currencyTable.row(i).node()).find('[type="checkbox"]');

            checkBox.prop('checked', false);

            this.findSelectedCurrenciesInTable(checkBox, currencyItemId);
        }
    };

    /**
     * Check for selected currencies in currency table.
     * @param {object} checkBox - Jquery object with checkbox.
     * @param {number} currencyItemId - Id if currency row.
     */
    this.findSelectedCurrenciesInTable = function (checkBox, currencyItemId) {
        var itemEqualId = function (item) {
            return item[0] === currencyItemId;
        };

        if (this.selectedCurrenciesData.some(itemEqualId)) {
            checkBox.prop('checked', true);
        }
    };

    /**
     * Update counter.
     */
    this.updateCounter = function () {
        var counterText = '';

        if (this.selectedCurrenciesData.length) {
            counterText = ' (' + this.selectedCurrenciesData.length + ')';
        }

        $(this.counterSelector).html(counterText);
    };

    /**
     * Update selected currencies input value.
     */
    this.updateSelectedCurrenciesInputValue = function () {
        var inputValue = this.selectedCurrenciesData.reduce(function (concat, current, index) {
            return index ? concat + ',' + current[1] : current[1];
        }, '');

        this.$inputWithSelectedCurrencies.val(inputValue);
    };

    /**
     * Add selected currency to array with all selected items.
     * @param {array} rowData - Array of all data selected currency.
     */
    this.addRow = function (rowData) {
        var currencyItem = rowData.slice();
        currencyItem[rowData.length - 1] = this.removeBtnTemplate.replace('#', currencyItem[1]);
        this.selectedCurrenciesData.push(currencyItem);
        this.renderSelectedItemsTable(currencyItem);
    };

    /**
     * Remove row from array with all selected items.
     * @param {string} code - Currencies code which should be deleted.
     */
    this.removeRow = function (code) {
        var self = this;

        this.selectedCurrenciesData.every(function (elem, index) {
            if (elem[1] !== code) {
                return true;
            }

            self.selectedCurrenciesData.splice(index, 1);
            return false;
        });
        self.renderSelectedItemsTable();
    };

    /**
     * Add event for remove button to remove row from array with all selected items.
     */
    this.addRemoveButtonClickHandler = function () {
        var self = this,
            selectedTable = this.$selectedCurrenciesTable;

        selectedTable.on('click', this.removeBtnSelector, function (e) {
            e.preventDefault();

            var id = $(e.target).attr('href');

            self.removeRow(id);
            self.updateCheckboxes();
        });
    };

    /**
     * Add counter template on init.
     */
    this.addCounterToLabel = function () {
        this.$counterLabel.append(this.counterTemplate);
    };

    /**
     * Redraw table with selected items.
     */
    this.renderSelectedItemsTable = function () {
        this.$selectedCurrenciesTable.DataTable().clear().rows.add(this.selectedCurrenciesData).draw();

        this.updateCounter();
        this.updateSelectedCurrenciesInputValue();
        this.updateCheckboxes();
    };
};

module.exports = SelectCurrencyTableAPI;
