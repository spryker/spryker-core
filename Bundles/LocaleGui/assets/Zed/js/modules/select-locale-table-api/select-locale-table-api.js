/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectLocaleTableAPI = function () {
    this.selectedLocalesData = [];
    this.removeBtnSelector = '.js-remove-item';
    this.removeBtnTemplate = '<a href="#" class="js-remove-item btn-xs">Remove</a>';
    this.counterSelector = '.js-counter';
    this.counterTemplate = '<span class="js-counter"></span>';
    this.initialDataLoaded = false;

    /**
     * Init all table adding functionality.
     * @param {string} localeTable - Current table with locales.
     * @param {string} selectedLocalesTable - Table where should locale be added.
     * @param {string} checkboxSelector - Checkbox selector.
     * @param {string} counterLabelSelector - Tabs label where will be added count of select locales.
     * @param {string} inputWithSelectedLocales - In this input will putted all selected locale ids.
     */
    this.init = function (
        localeTable,
        selectedLocalesTable,
        checkboxSelector,
        counterLabelSelector,
        inputWithSelectedLocales,
    ) {
        this.$localeTable = $(localeTable);
        this.$selectedLocalesTable = $(selectedLocalesTable);
        this.$counterLabel = $(counterLabelSelector);
        this.$inputWithSelectedLocales = $(inputWithSelectedLocales);
        this.checkboxSelector = checkboxSelector;
        this.counterSelector = counterLabelSelector + ' ' + this.counterSelector;

        this.drawLocalesTable();
        this.addRemoveButtonClickHandler();
        this.addCounterToLabel();
    };

    this.selectLocalesOnLoad = function (initialSelectedLocalesData) {
        if (this.initialDataLoaded) {
            return;
        }

        var data = initialSelectedLocalesData.replace(/&quot;/g, '"').replace(/,/g, '');
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
    this.drawLocalesTable = function () {
        var self = this;
        var localeTableData = self.$localeTable.DataTable();
        localeTableData.on('draw', function (event, settings) {
            self.updateCheckboxes();
            self.mapEventsToCheckboxes(
                localeTableData,
                $('#' + self.$localeTable.attr('id') + ' ' + self.checkboxSelector),
            );

            if (self.$inputWithSelectedLocales && initialSelectedLocalesData) {
                var initialSelectedLocalesData = self.$inputWithSelectedLocales.val();
                self.selectLocalesOnLoad(initialSelectedLocalesData);
                self.$inputWithSelectedLocales.val('');
            }
        });
    };

    /**
     * Add change event for all checkboxes checkbox. Fires every time, when locale table redraws.
     * @param {object} localeTableData - DataTable options ( get by $(element).DataTable() ).
     * @param {checkboxes} checkboxes - Collection of all checkboxes in Locale Table.
     */
    this.mapEventsToCheckboxes = function (localeTableData, checkboxes) {
        var self = this;

        checkboxes.off('change');
        checkboxes.on('change', function () {
            var rowIndex = checkboxes.index($(this)),
                rowData = localeTableData.data()[rowIndex],
                id = rowData[0];

            if ($(this).is(':checked')) {
                return self.addRow(rowData);
            }

            return self.removeRow(id);
        });
    };

    /**
     * Check for selected locales in locale table.
     */
    this.updateCheckboxes = function () {
        var localeTable = this.$localeTable.DataTable(),
            localeTableData = localeTable.data();

        for (var i = 0; i < localeTableData.length; i++) {
            var localeItemData = localeTableData[i],
                localeItemId = localeItemData[0],
                checkBox = $(localeTable.row(i).node()).find('[type="checkbox"]');

            checkBox.prop('checked', false);

            this.findSelectedLocalesInTable(checkBox, localeItemId);
        }
    };

    /**
     * Check for selected locales in locale table.
     * @param {object} checkBox - Jquery object with checkbox.
     * @param {number} localeItemId - Id if locale row.
     */
    this.findSelectedLocalesInTable = function (checkBox, localeItemId) {
        var itemEqualId = function (item) {
            return item[0] === localeItemId;
        };

        if (this.selectedLocalesData.some(itemEqualId)) {
            checkBox.prop('checked', true);
        }
    };

    /**
     * Update counter.
     */
    this.updateCounter = function () {
        var counterText = '';

        if (this.selectedLocalesData.length) {
            counterText = ' (' + this.selectedLocalesData.length + ')';
        }

        $(this.counterSelector).html(counterText);
    };

    /**
     * Update selected locales input value.
     */
    this.updateSelectedLocalesInputValue = function () {
        var inputValue = this.selectedLocalesData.reduce(function (concat, current, index) {
            return index ? concat + ',' + current[0] : current[0];
        }, '');

        this.$inputWithSelectedLocales.val(inputValue);
    };

    /**
     * Add selected locale to array with all selected items.
     * @param {array} rowData - Array of all data selected locale.
     */
    this.addRow = function (rowData) {
        var localeItem = rowData.slice();
        localeItem[rowData.length - 1] = this.removeBtnTemplate.replace('#', localeItem[0]);
        this.selectedLocalesData.push(localeItem);
        this.renderSelectedItemsTable(localeItem);
    };

    /**
     * Remove row from array with all selected items.
     * @param {string} code - Locales code which should be deleted.
     */
    this.removeRow = function (code) {
        var self = this;

        this.selectedLocalesData.every(function (elem, index) {
            if (elem[0] !== code) {
                return true;
            }

            self.selectedLocalesData.splice(index, 1);
            return false;
        });
        self.renderSelectedItemsTable();
    };

    /**
     * Add event for remove button to remove row from array with all selected items.
     */
    this.addRemoveButtonClickHandler = function () {
        var self = this,
            selectedTable = this.$selectedLocalesTable;

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
        this.$selectedLocalesTable.DataTable().clear().rows.add(this.selectedLocalesData).draw();

        this.updateCounter();
        this.updateSelectedLocalesInputValue();
        this.updateCheckboxes();
    };
};

module.exports = SelectLocaleTableAPI;
