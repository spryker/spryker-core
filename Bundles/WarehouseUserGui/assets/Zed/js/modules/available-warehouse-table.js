/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const { RelatedWarehouseTable } = require('./related-warehouse-table/related-warehouse-table');

function AvailableWarehouseTable() {
    const _self = this;
    this.sourceTabSelector = '#available-tab';
    this.sourceTableSelector = `${this.sourceTabSelector} .table`;
    this.selectAllButtonSelector = `${this.sourceTabSelector} .js-select-all-button`;
    this.destinationTabSelector = '#to-be-assigned-tab';
    this.destinationTabLabelSelector = `${this.destinationTabSelector}-label`;
    this.destinationTableSelector = `${this.destinationTabSelector}-table`;
    this.formFieldSelector = '#warehouseUser_uuidsWarehousesToAssign';
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
            onRemoveCallback: this.onRemove,
        });

        $(this.selectAllButtonSelector).on('click', () =>
            this.relatedWarehouseTable.tableHandler.toggleCheckboxes(true),
        );
    };

    this.onRemove = function () {
        const uuid = $(this).data('uuid');
        const tableHandler = _self.relatedWarehouseTable.tableHandler;

        $(_self.destinationTableSelector).DataTable().row($(this).parents('tr')).remove().draw();
        tableHandler.warehouseIdSelector.removeIdFromSelection(uuid);
        tableHandler.updateSelectedWarehousesLabelCount();
        $(`input[value="${uuid}"]`, $(_self.sourceTableSelector)).prop('checked', false);
    };

    this.init();
}

module.exports = AvailableWarehouseTable;
