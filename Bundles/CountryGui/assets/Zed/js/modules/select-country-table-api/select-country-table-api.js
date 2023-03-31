/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SelectCountryTableAPI = function () {
    this.selectedCountriesData = [];
    this.removeBtnSelector = '.js-remove-item';
    this.removeBtnTemplate = '<a href="#" class="js-remove-item btn-xs">Remove</a>';
    this.counterSelector = '.js-counter';
    this.counterTemplate = '<span class="js-counter"></span>';
    this.initialDataLoaded = false;

    /**
     * Init all table adding functionality.
     * @param {string} countryTable - Current table with countries.
     * @param {string} selectedCountriesTable - Table where should country be added.
     * @param {string} checkboxSelector - Checkbox selector.
     * @param {string} counterLabelSelector - Tabs label where will be added count of select countries.
     * @param {string} inputWithSelectedCountries - In this input will putted all selected country ids.
     */
    this.init = function (
        countryTable,
        selectedCountriesTable,
        checkboxSelector,
        counterLabelSelector,
        inputWithSelectedCountries,
    ) {
        this.$countryTable = $(countryTable);
        this.$selectedCountriesTable = $(selectedCountriesTable);
        this.$counterLabel = $(counterLabelSelector);
        this.$inputWithSelectedCountries = $(inputWithSelectedCountries);
        this.checkboxSelector = checkboxSelector;
        this.counterSelector = counterLabelSelector + ' ' + this.counterSelector;

        this.drawCountriesTable();
        this.addRemoveButtonClickHandler();
        this.addCounterToLabel();
    };

    this.selectCountriesOnLoad = function (initialSelectedCountriesData) {
        if (this.initialDataLoaded) {
            return;
        }

        var data = initialSelectedCountriesData.replace(/&quot;/g, '"').replace(/,/g, '');
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
    this.drawCountriesTable = function () {
        var self = this;
        var countryTableData = self.$countryTable.DataTable();
        countryTableData.on('draw', function (event, settings) {
            self.updateCheckboxes();
            self.mapEventsToCheckboxes(
                countryTableData,
                $('#' + self.$countryTable.attr('id') + ' ' + self.checkboxSelector),
            );

            if (self.$inputWithSelectedCountries && initialSelectedCountriesData) {
                var initialSelectedCountriesData = self.$inputWithSelectedCountries.val();
                self.selectCountriesOnLoad(initialSelectedCountriesData);
                self.$inputWithSelectedCountries.val('');
            }
        });
    };

    /**
     * Add change event for all checkboxes checkbox. Fires every time, when country table redraws.
     * @param {object} countryTableData - DataTable options ( get by $(element).DataTable() ).
     * @param {checkboxes} checkboxes - Collection of all checkboxes in Country Table.
     */
    this.mapEventsToCheckboxes = function (countryTableData, checkboxes) {
        var self = this;

        checkboxes.off('change');
        checkboxes.on('change', function () {
            var rowIndex = checkboxes.index($(this)),
                rowData = countryTableData.data()[rowIndex],
                id = rowData[0];

            if ($(this).is(':checked')) {
                return self.addRow(rowData);
            }

            return self.removeRow(id);
        });
    };

    /**
     * Check for selected countries in country table.
     */
    this.updateCheckboxes = function () {
        var countryTable = this.$countryTable.DataTable(),
            countryTableData = countryTable.data();

        for (var i = 0; i < countryTableData.length; i++) {
            var countryItemData = countryTableData[i],
                countryItemId = countryItemData[0],
                checkBox = $(countryTable.row(i).node()).find('[type="checkbox"]');

            checkBox.prop('checked', false);

            this.findSelectedCountriesInTable(checkBox, countryItemId);
        }
    };

    /**
     * Check for selected countries in country table.
     * @param {object} checkBox - Jquery object with checkbox.
     * @param {number} countryItemId - Id if country row.
     */
    this.findSelectedCountriesInTable = function (checkBox, countryItemId) {
        var itemEqualId = function (item) {
            return item[0] === countryItemId;
        };

        if (this.selectedCountriesData.some(itemEqualId)) {
            checkBox.prop('checked', true);
        }
    };

    /**
     * Update counter.
     */
    this.updateCounter = function () {
        var counterText = '';

        if (this.selectedCountriesData.length) {
            counterText = ' (' + this.selectedCountriesData.length + ')';
        }

        $(this.counterSelector).html(counterText);
    };

    /**
     * Update selected countries input value.
     */
    this.updateSelectedCountriesInputValue = function () {
        var inputValue = this.selectedCountriesData.reduce(function (concat, current, index) {
            return index ? concat + ',' + current[1] : current[1];
        }, '');

        this.$inputWithSelectedCountries.val(inputValue);
    };

    /**
     * Add selected country to array with all selected items.
     * @param {array} rowData - Array of all data selected country.
     */
    this.addRow = function (rowData) {
        var countryItem = rowData.slice();
        countryItem[rowData.length - 1] = this.removeBtnTemplate.replace('#', countryItem[1]);
        this.selectedCountriesData.push(countryItem);
        this.renderSelectedItemsTable(countryItem);
    };

    /**
     * Remove row from array with all selected items.
     * @param {string} code - Countries code which should be deleted.
     */
    this.removeRow = function (code) {
        var self = this;

        this.selectedCountriesData.every(function (elem, index) {
            if (elem[1] !== code) {
                return true;
            }

            self.selectedCountriesData.splice(index, 1);
            return false;
        });
        self.renderSelectedItemsTable();
    };

    /**
     * Add event for remove button to remove row from array with all selected items.
     */
    this.addRemoveButtonClickHandler = function () {
        var self = this,
            selectedTable = this.$selectedCountriesTable;

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
        this.$selectedCountriesTable.DataTable().clear().rows.add(this.selectedCountriesData).draw();

        this.updateCounter();
        this.updateSelectedCountriesInputValue();
        this.updateCheckboxes();
    };
};

module.exports = SelectCountryTableAPI;
