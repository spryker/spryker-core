/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ImageZoom = require('js-image-zoom');

$(document).ready(function () {
    var imagePreview = document.getElementById('preview');

    new ImageZoom(imagePreview, {
        width: 200,
        height: 400,
        zoomWidth: 350,
        offset: {
            vertical: 0,
            horizontal: 10
        }
    });
});
