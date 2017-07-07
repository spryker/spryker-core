/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    var $checkbox = $('.js-is-dynamic-label');

    $('.gui-table-data, .gui-table-data-no-search').on('draw.dt', function() {
        changeProductsTabState($checkbox);
    });

    $checkbox.on('change', function() {
        changeProductsTabState(this);
    });
});

/**
 * @param {Object} checkbox
 */
function changeProductsTabState(checkbox) {
    var checked = $(checkbox).is(':checked');
    var $tabContent =  $('#tab-content-product-assignment');

    if (checked) {
        $tabContent.find(':checkbox').attr('disabled', true);
        $tabContent.find('a.btn').attr('disabled', true);
        $tabContent.find('a.btn').css('pointer-events', 'none');
        return;
    }

    $tabContent.find(':checkbox').removeAttr('disabled');
    $tabContent.find('a.btn').removeAttr('disabled');
    $tabContent.find('a.btn').css('pointer-events', 'auto');
}
