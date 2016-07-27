/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready(function() {

    var valueCount = 0;
    $('#add-another-option').click(function(e) {
        e.preventDefault();

        var valueList = $('#option-value-list');

        var newOptionWidget = valueList.data('prototype');
        newOptionWidget = newOptionWidget.replace(/__name__/g, valueCount);
        valueCount++;

        valueList.append(newOptionWidget);
    });
});
