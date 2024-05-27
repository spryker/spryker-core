/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $('[name=dynamic-entity]').submit(function (event) {
        var isDeletableChecked = $('#dynamic-entity_is_deletable').is(':checked');
        var deletionAllowMessage = $('#dynamic-entity_is_deletable').attr('data-deletion-allow-message');
        if (isDeletableChecked && !confirm(deletionAllowMessage)) {
            event.preventDefault();
        }
    });
});
