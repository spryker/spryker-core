/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var saver = require('./saver');
var progressBar = require('../shared/progress-bar');

var SELECTOR_CATEGORY_LIST = '#category-list';
var SELECTOR_SAVE_BUTTON = '#save-button';

jQuery(document).ready(function () {
    progressBar.setSelector('#progress-bar');

    var categoryNestable = jQuery(SELECTOR_CATEGORY_LIST).nestable({
        depth: 1,
    });

    categoryNestable.on('change', function (event) {
        var list = event.length ? event : jQuery(event.target);
        window.serializedList = window.JSON.stringify(list.nestable('serialize'));
    });

    jQuery(SELECTOR_SAVE_BUTTON).on('click', function () {
        saver.save(window.serializedList, progressBar);
    });
});
