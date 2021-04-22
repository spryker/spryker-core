/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SELECTOR_DELETE_CONFIRM = '#delete_confirm';
var SELECTOR_SUBMIT_DELETE = '#submit_delete';

$(document).ready(function () {
    var confirmCheckboxElement = $(SELECTOR_DELETE_CONFIRM);

    confirmCheckboxElement.off('click').on('click', function () {
        var checkboxIsChecked = $confirmCheckboxElement.prop('checked');

        var submitDeleteElement = $(SELECTOR_SUBMIT_DELETE);
        submitDeleteElement.prop('disabled', !checkboxIsChecked);
    });
});
