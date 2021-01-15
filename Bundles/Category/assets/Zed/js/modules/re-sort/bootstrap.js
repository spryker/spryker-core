/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var writer = require('./writer');
var progressBar = require('../shared/progress-bar');

jQuery(document).ready(function () {
    progressBar.setSelector('#progress-bar');

    var categoryNestable = jQuery('#category-list').nestable({
        depth: 1,
    });

    categoryNestable.on('change', function (event) {
        var list = event.length ? event : jQuery(event.target);
        window.serializedList = window.JSON.stringify(list.nestable('serialize'));
    });

    jQuery('#save-button').on('click', function () {
        writer.save(window.serializedList, progressBar);
    });
});
