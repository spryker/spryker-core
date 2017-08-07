/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready(function() {
    var productReviewTable = $('#product-review-table').DataTable();

    // TODO: add empty first column to table that shows an extend/collapse icon according to the state of the details
    // TODO: add css to style the detail tables
    // TODO: move table related stuff to "product-review-table.js" and clean up code

    $('#product-review-table tbody').on('click', 'tr', function(e) {
        if (!$(e.target).is('td')) {
            return;
        }

        var tr = $(this);
        var row = productReviewTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(row.data().details).show();
            tr.addClass('shown');
        }
    });

});
