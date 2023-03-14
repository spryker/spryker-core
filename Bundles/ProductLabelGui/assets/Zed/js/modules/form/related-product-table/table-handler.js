/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ProductSelector = require('./product-selector');

var CHECKBOX_CHECKED_STATE_CHECKED = 'checked';
var CHECKBOX_CHECKED_STATE_UN_CHECKED = 'un_checked';

/**
 * @param {string} sourceTable
 * @param {string} destinationTable
 * @param {string} labelCaption
 * @param {string} labelId
 * @param {string} formFieldId
 * @param {function} onRemoveCallback
 *
 * @return {object}
 */
function TableHandler(sourceTable, destinationTable, labelCaption, labelId, formFieldId, onRemoveCallback) {
    var tableHandler = {
        labelId: labelId,
        labelCaption: labelCaption,
        formFieldId: formFieldId,
        sourceTable: sourceTable,
        destinationTable: destinationTable,
    };

    var destinationTableProductSelector = ProductSelector.create();

    tableHandler.toggleSelection = function () {
        $('input[type="checkbox"]', sourceTable).each(function (index, checkboxNode) {
            var $checkbox = $(checkboxNode);
            $checkbox.prop('checked', !$checkbox.prop('checked'));
            $checkbox.trigger('change');
        });

        return false;
    };

    tableHandler.selectAll = function () {
        $('input[type="checkbox"]', sourceTable).each(function (index, checkboxNode) {
            var $checkbox = $(checkboxNode);
            $checkbox.prop('checked', true);
            $checkbox.trigger('change');
        });

        return false;
    };

    tableHandler.deSelectAll = function () {
        $('input[type="checkbox"]', sourceTable).each(function (index, checkboxNode) {
            var $checkbox = $(checkboxNode);
            $checkbox.prop('checked', false);
            $checkbox.trigger('change');
        });

        return false;
    };

    tableHandler.addSelectedProduct = function (idProduct, sku, name) {
        idProduct = parseInt(idProduct, 10);
        if (destinationTableProductSelector.isProductSelected(idProduct)) {
            return;
        }
        destinationTableProductSelector.addProductToSelection(idProduct);

        destinationTable
            .DataTable()
            .row.add([
                idProduct,
                decodeURIComponent(String(sku).replace(/\+/g, '%20')),
                String(name),
                '<div><a data-id="' + idProduct + '" href="#" class="btn btn-xs remove-item">Remove</a></div>',
            ])
            .draw();

        $('.remove-item').off('click');
        $('.remove-item').on('click', onRemoveCallback);

        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.removeSelectedProduct = function (idProduct) {
        idProduct = parseInt(idProduct, 10);

        destinationTable
            .DataTable()
            .rows()
            .every(function (rowIndex, tableLoop, rowLoop) {
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
                tableHandler.unCheckCheckbox($checkbox);
            });

        destinationTable.DataTable().draw();
        tableHandler.updateSelectedProductsLabelCount();
    };

    tableHandler.getSelector = function () {
        return destinationTableProductSelector;
    };

    tableHandler.updateSelectedProductsLabelCount = function () {
        $(tableHandler.getLabelId()).text(
            labelCaption + ' (' + Object.keys(this.getSelector().getSelected()).length + ')',
        );
        var productIds = Object.keys(this.getSelector().getSelected());
        var s = productIds.join(',');
        var field = $('#' + tableHandler.getFormFieldId());
        field.attr('value', s);
    };

    tableHandler.getLabelId = function () {
        return tableHandler.labelId;
    };

    tableHandler.getLabelCaption = function () {
        return tableHandler.labelCaption;
    };

    tableHandler.getFormFieldId = function () {
        return tableHandler.formFieldId;
    };

    tableHandler.getSourceTable = function () {
        return tableHandler.sourceTable;
    };

    tableHandler.getDestinationTable = function () {
        return tableHandler.destinationTable;
    };

    /**
     * @returns {string}
     */
    tableHandler.getInitialCheckboxCheckedState = function () {
        return CHECKBOX_CHECKED_STATE_UN_CHECKED;
    };

    /**
     * @param {jQuery} $checkbox
     * @return {boolean}
     */
    tableHandler.isCheckboxActive = function ($checkbox) {
        if (tableHandler.getInitialCheckboxCheckedState() === CHECKBOX_CHECKED_STATE_UN_CHECKED) {
            return $checkbox.prop('checked');
        }

        return !$checkbox.prop('checked');
    };

    /**
     * @param {jQuery} $checkbox
     */
    tableHandler.checkCheckbox = function ($checkbox) {
        var checkedState = tableHandler.getInitialCheckboxCheckedState() === CHECKBOX_CHECKED_STATE_UN_CHECKED;
        $checkbox.prop('checked', checkedState);
    };

    /**
     * @param {jQuery} $checkbox
     */
    tableHandler.unCheckCheckbox = function ($checkbox) {
        var checkedState = tableHandler.getInitialCheckboxCheckedState() !== CHECKBOX_CHECKED_STATE_UN_CHECKED;
        $checkbox.prop('checked', checkedState);
    };

    return tableHandler;
}

module.exports = {
    /**
     * @param {string} sourceTable
     * @param {string} destinationTable
     * @param {string} labelCaption
     * @param {string} labelId
     * @param {string} formFieldId
     * @param {function} onRemoveCallback
     *
     * @return {TableHandler}
     */
    create: function (sourceTable, destinationTable, labelCaption, labelId, formFieldId, onRemoveCallback) {
        return new TableHandler(sourceTable, destinationTable, labelCaption, labelId, formFieldId, onRemoveCallback);
    },
    CHECKBOX_CHECKED_STATE_CHECKED: CHECKBOX_CHECKED_STATE_CHECKED,
    CHECKBOX_CHECKED_STATE_UN_CHECKED: CHECKBOX_CHECKED_STATE_UN_CHECKED,
};
