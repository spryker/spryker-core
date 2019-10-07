/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var config = {
    slotTableColumnsMapping: {
        idSlot: 0,
        slotName: 1
    }
};

var selectedIdSlot = 0,
    $slotTableWrapper = $('#slot-table-wrapper'),
    $slotProductsTableWrapper = $('#slot-products-table-wrapper'),
    $slotProductsTableName = $('#slot-products-table-name');

/**
 * @return {void}
 */
function init() {
    addSlotTableRowClickHandler();
    addSlotTableDrawHandler();
}

/**
 * @return {void}
 */
function addSlotTableRowClickHandler() {
    var $slotTable = $slotTableWrapper.find('.dataTables_scrollBody table').first().DataTable();

    $slotTable.on('click', 'tbody tr[role="row"]', function () {
        var rowData = $slotTable.row(this).data();
        var idSlot = rowData[config.slotTableColumnsMapping.idSlot];

        if (idSlot !== selectedIdSlot) {
            selectedIdSlot = idSlot;

            loadSlotProductsTable();
            markSelectedRow($(this));
            $slotProductsTableName.text(rowData[config.slotTableColumnsMapping.slotName]);
        }
    });
}

/**
 * @return {void}
 */
function addSlotTableDrawHandler() {
    var $slotTable = $slotTableWrapper.find('.dataTables_scrollBody table').first().DataTable();

    $slotTable.on('draw', function () {
        var $rows = $(this).find('tbody > tr[role="row"]');

        selectedIdSlot = getInitialSelectedIdSlot($rows);

        // initial draw
        if (selectedIdSlot === 0) {
            var $row = $rows.first();

            if ($row) {
                $row.trigger('click');
                $slotProductsTableWrapper.removeClass('hidden');
            }

            return;
        }

        //redraw
        $.each($rows, function (index, row) {
            if ($slotTable.row(row).data()[config.slotTableColumnsMapping.idSlot] === selectedIdSlot) {
                markSelectedRow($(row));
            }
        });
    });

}

/**
 * @return {void}
 */
function loadSlotProductsTable() {
    var $slotProductsTable = $slotProductsTableWrapper.find('.dataTables_scrollBody table').first(),
        slotProductsTableLoadUrl = '/configurable-bundle-gui/template/slot-products-table?id-configurable-bundle-template-slot=';

    $slotProductsTable.DataTable().ajax.url(slotProductsTableLoadUrl + selectedIdSlot).load();
}

/**
 * @return {void}
 */
function markSelectedRow($row) {
    $row.siblings('tr').removeClass('selected');
    $row.addClass('selected');
}

function getInitialSelectedIdSlot() {
    var selectedIdSlot = $('#selected-id-configurable-bundle-template-slot').val();

    if (selectedIdSlot.length) {
        return parseInt(selectedIdSlot);
    }

    return 0;
}

/**
 * Open public methods
 */
module.exports = {
    init: init
};
