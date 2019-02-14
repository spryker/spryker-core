/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {

    var bundledProductElement = $('#table-bundled-products');
    var bundledProductTable = bundledProductElement.DataTable({
        scrollX: 'auto',
        autoWidth: false,
        destroy: true
    });

    var availabilityTable = $('#availability-table').DataTable({
        destroy: true,
        scrollX: 'auto',
        autoWidth: false,
        fnInitComplete: function(oSettings, json) {
            $('#availability-table .btn-view').each(function(index, element) {

                $(element).on('click', function(event) {
                    $('#bundled-products').show();
                    event.preventDefault();

                    var tableDataUrl = $(element).prop('href');
                    bundledProductTable.ajax.url(tableDataUrl).load();
                });

            });
        }
    });



});
