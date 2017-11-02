/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('./filters');

$(document).ready(function() {
    $('#addButton').on('click', function() {

    });
});

/**
 * @return {void}
 */
function triggerResize() { 
    var resizeEvent = new Event('resize');
    window.dispatchEvent(resizeEvent);
}
