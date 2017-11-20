/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

var filters = $('#product_category_filter_filters');
var filtersContainer = $('#filter-container');
var list = $('#filter-container ol');


$(document).ready(function() {
    filtersContainer.nestable({
        group: 1,
        maxDepth: 1
    }).on('change', function(e) {
        var list = e.length ? e : $(e.target);
        filters.val(JSON.stringify(list.nestable('serialize').map(function(value) {
            return value.filter;
        })));
    });

    filtersContainer.trigger('change');
});

function getCurrentList() {
    return filters.val();
}

function addToList(filterToAdd, count) {
    list.append('<li data-filter="' + filterToAdd + '" class="filter-item dd-item">\n' +
        '                            <div class="dd-handle">\n' +
        '                                <a class="btn btn-xs btn-outline btn-danger" title="Remove Filter">\n' +
        '                                    <i class="fa fa-fw fa-trash"></i>\n' +
        '                                </a>\n' +
                                        filterToAdd + ' (' + count + ')' +
        '                            </div>\n' +
        '                        </li>');
    filtersContainer.trigger('change');
}

module.exports = {
    getCurrentList: getCurrentList,
    addToList: addToList
};
