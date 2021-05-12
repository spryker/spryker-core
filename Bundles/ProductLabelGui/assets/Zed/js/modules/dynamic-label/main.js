/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $('.gui-table-data, .gui-table-data-no-search').on('draw.dt', function () {
        disableInputs();
    });

    disableInputs(this);
});

function disableInputs() {
    var $tabContent = $('#tab-content-product-assignment');

    $tabContent.find(':checkbox').attr('disabled', true);
    $tabContent.find('a.btn').attr('disabled', true);
    $tabContent.find('a.btn').css('pointer-events', 'none');
}
