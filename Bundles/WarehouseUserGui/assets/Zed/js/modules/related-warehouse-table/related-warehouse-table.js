/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const { TableHandler, CHECKBOX_CHECKED_STATE_CHECKED } = require('./table-handler');

/**
 * @param {Object} options
 * @param {jQuery} options.$sourceTable
 * @param {jQuery} options.$destinationTable
 * @param {jQuery} options.$label
 * @param {jQuery} options.$formField
 * @param {string} options.checkboxSelector
 * @param {string} options.labelCaption
 * @param {string} [options.initialCheckboxCheckedState]
 * @param {function} options.onRemoveCallback
 */
function RelatedWarehouseTable(options) {
    const _self = this;
    this.tableHandler = null;

    $.extend(this, options);

    this.init = () => {
        this.$destinationTable.DataTable({ destroy: true });

        this.tableHandler = new TableHandler({
            $sourceTable: this.$sourceTable,
            $destinationTable: this.$destinationTable,
            $label: this.$label,
            $formField: this.$formField,
            labelCaption: this.labelCaption,
            initialCheckboxCheckedState: this.initialCheckboxCheckedState,
            onRemoveCallback: this.onRemoveCallback,
        });

        this.$sourceTable.DataTable().on('draw', (event, settings) => {
            $(_self.checkboxSelector, $(_self.$sourceTable)).off('change');

            $(_self.checkboxSelector, $(_self.$sourceTable)).on('change', function () {
                const info = $.parseJSON($(this).attr('data-info'));

                if (_self.tableHandler.isCheckboxActive($(this))) {
                    _self.tableHandler.addSelectedWarehouse(
                        info.idWarehouse,
                        info.warehouseUuid,
                        info.name,
                        info.status,
                    );
                } else {
                    _self.tableHandler.removeSelectedWarehouse(info.idWarehouse, info.warehouseUuid);
                }
            });
        });
    };

    this.init();
}

module.exports = {
    RelatedWarehouseTable,
    CHECKBOX_CHECKED_STATE_CHECKED,
};
