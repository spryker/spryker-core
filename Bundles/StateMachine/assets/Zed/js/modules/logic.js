/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

import Drift from 'drift-zoom';

$(document).ready(function () {
    var imagePreview = document.getElementsByClassName('preview-image')[0];
    var zoomContainer = document.getElementsByClassName('zoom-container')[0];

    new Drift(imagePreview, {
        containInline: true,
        sourceAttribute: 'src',
        hoverBoundingBox: true,
        paneContainer: zoomContainer,
        inlinePane: 900,
        inlineOffsetY: -85,
    });
});
