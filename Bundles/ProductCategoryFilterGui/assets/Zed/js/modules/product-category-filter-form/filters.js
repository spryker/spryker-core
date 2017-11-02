/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

var filters = $('#filters');


$(document).ready(function() {
    $('#filter-container').nestable({
        group: 1,
        maxDepth: 1
    }).on('change', function(e) {
        var list = e.length ? e : $(e.target);
        filters.val(list.nestable('serialize'));
    });
});
