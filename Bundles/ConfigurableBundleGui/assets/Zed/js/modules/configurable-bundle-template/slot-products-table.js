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

function init() {
    addSlotTableRowClickHandler();
    addSlotTableDrawHandler();
}

function addSlotTableRowClickHandler() {
    var $slotTable = $slotTableWrapper.find('.dataTables_scrollBody table').first().DataTable();

    $slotTable.on('click', 'tbody tr[role="row"]', function () {
        updateSlotProductsTable(this, $slotTable);
    });
}

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

function loadSlotProductsTable() {
    var $slotProductsTable = $slotProductsTableWrapper.find('.dataTables_scrollBody table').first(),
        slotProductsTableLoadUrl = '/configurable-bundle-gui/template/slot-products-table?id-configurable-bundle-template-slot=';

    $slotProductsTable.DataTable().ajax.url(slotProductsTableLoadUrl + selectedIdSlot).load();
}

function markSelectedRow($row) {
    $row.siblings().removeClass('selected');
    $row.addClass('selected');
}

function getInitialSelectedIdSlot() {
    var selectedIdSlot = $('#selected-id-configurable-bundle-template-slot').val();

    return selectedIdSlot.length ? parseInt(selectedIdSlot) : 0;
}

function performInitialDraw($slotTable, $rows) {
    isInitialDraw = false;
    $slotProductsTableWrapper.removeClass('hidden');

    if (!$rows.length) {
        return;
    }

    var initialSelectedIdSlot = getInitialSelectedIdSlot();

    if (!initialSelectedIdSlot) {
        updateSlotProductsTable($rows.first(), $slotTable);

        return;
    }

    $.each($rows, function (index, row) {
        if ($slotTable.row(row).data()[config.slotTableColumnsMapping.idSlot] === initialSelectedIdSlot) {
            updateSlotProductsTable(row, $slotTable);
        }
    });
}

function updateSlotProductsTable(row, $slotTable) {
    var rowData = $slotTable.row(row).data();
    var idSlot = rowData[config.slotTableColumnsMapping.idSlot];

    if (idSlot === selectedIdSlot) {
        return;
    }

    selectedIdSlot = idSlot;
    loadSlotProductsTable();
    markSelectedRow($(row));
    $slotProductsTableName.text(rowData[config.slotTableColumnsMapping.slotName]);
}

module.exports = {
    init: init
};
