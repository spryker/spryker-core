/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    var productReviewTable = $('#product-review-table').DataTable();

    $('#product-review-table tbody').on('click', 'tr', function (e) {
        var arrow = $(e.target);
        if (!arrow.is('i.fa-chevron-down') && !arrow.is('i.fa-chevron-up')) {
            return;
        }

        var tr = $(this).closest('tr');
        var row = productReviewTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(row.data().details).show();
            tr.addClass('shown');
        }

        arrow.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    });
});
