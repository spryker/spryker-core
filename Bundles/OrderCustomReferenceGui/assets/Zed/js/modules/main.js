/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    var $wrapper = $('.js-customer-order-reference');
    var $target = $('.js-toggle-target');
    var triggerSelector = '.js-toggle-trigger';

    $wrapper.on('click', triggerSelector, function () {
        $target.each(function () {
            $(this).toggleClass('hide');
        });
    });
});
