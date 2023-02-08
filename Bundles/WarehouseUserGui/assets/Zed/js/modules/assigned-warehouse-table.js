/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const {
    RelatedWarehouseTable,
    CHECKBOX_CHECKED_STATE_CHECKED,
} = require('./related-warehouse-table/related-warehouse-table');

function AssignedWarehouseTable() {
    const _self = this;
    this.sourceTabSelector = '#assigned-tab';
    this.sourceTableSelector = `${this.sourceTabSelector} .table`;
    this.deselectAllButtonSelector = `${this.sourceTabSelector} .js-de-select-all-button`;
    this.destinationTabSelector = '#deassigned-tab';
    this.destinationTabLabelSelector = `${this.destinationTabSelector}-label`;
    this.destinationTableSelector = `${this.destinationTabSelector}-table`;
    this.formFieldSelector = '#warehouseUser_uuidsWarehousesToDeassign';
    this.checkboxSelector = '.js-warehouse-checkbox';
    this.relatedWarehouseTable = null;

    this.init = () => {
        this.relatedWarehouseTable = new RelatedWarehouseTable({
            $sourceTable: $(this.sourceTableSelector),
            $destinationTable: $(this.destinationTableSelector),
            $label: $(this.destinationTabLabelSelector),
            $formField: $(this.formFieldSelector),
            checkboxSelector: this.checkboxSelector,
            labelCaption: $(this.destinationTabLabelSelector).text(),
            initialCheckboxCheckedState: CHECKBOX_CHECKED_STATE_CHECKED,
            onRemoveCallback: this.onRemove,
        });

        $(this.deselectAllButtonSelector).on('click', () =>
            this.relatedWarehouseTable.tableHandler.toggleCheckboxes(false),
        );
    };

    this.onRemove = function () {
        const uuid = $(this).data('uuid');
        const tableHandler = _self.relatedWarehouseTable.tableHandler;

        $(_self.destinationTableSelector).DataTable().row($(this).parents('tr')).remove().draw();
        tableHandler.warehouseIdSelector.removeIdFromSelection(uuid);
        tableHandler.updateSelectedWarehousesLabelCount();
        $(`input[value="${uuid}"]`, $(_self.sourceTableSelector)).prop('checked', true);
    };

    this.init();
}

module.exports = AssignedWarehouseTable;
