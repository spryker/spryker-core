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

var isInitialDraw = true,
    selectedIdSlot = 0,
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

        if (isInitialDraw) {
            performInitialDraw($slotTable, $rows);
        }

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

/**
 * @return {int}
 */
function getInitialSelectedIdSlot() {
    var selectedIdSlot = $('#selected-id-configurable-bundle-template-slot').val();

    if (selectedIdSlot.length) {
        return parseInt(selectedIdSlot);
    }

    return 0;
}

/**
 * @return {void}
 */
function performInitialDraw($slotTable, $rows) {
    isInitialDraw = false;
    $slotProductsTableWrapper.removeClass('hidden');

    if (!$rows.length) {
        return;
    }

    var initialSelectedIdSlot = getInitialSelectedIdSlot();

    if (initialSelectedIdSlot === 0) {
        $rows.first().click();

        return;
    }

    $.each($rows, function (index, row) {
        if ($slotTable.row(row).data()[config.slotTableColumnsMapping.idSlot] === initialSelectedIdSlot) {
            $(row).click();

            return;
        }
    });
}


/**
 * Open public methods
 */
module.exports = {
    init: init
};
