/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    var $confirmCheckboxElement = $('#delete_confirm');

    $confirmCheckboxElement.off('click').on('click', function () {
        var checkboxIsChecked = $confirmCheckboxElement.prop('checked');
        $('#submit_delete').prop('disabled', !checkboxIsChecked);
    });
});
