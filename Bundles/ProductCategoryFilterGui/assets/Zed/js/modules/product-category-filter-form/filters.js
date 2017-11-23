/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

var filters = $('#product_category_filter_filters');
var activeFiltersContainer = $('#filters-container');
var activeFilters = $('#filters-container ol');
var inactiveFiltersContainer = $('#inactive-filters-container');
var inactiveFilters = $('#inactive-filters-container ol');
var removeAllButton = $('#remove-all-filters');


$(document).ready(function() {
    activeFiltersContainer.nestable({
        group: 1,
        maxDepth: 1
    }).on('change', function() {
        filters.val(JSON.stringify(getAllFilters()));

        if(inactiveFiltersContainer.find('li').length === 0) {
            inactiveFiltersContainer.closest('.row').addClass('hidden');
        } else {
            inactiveFiltersContainer.closest('.row').removeClass('hidden');
        }
    });

    activeFiltersContainer.trigger('change');

    activeFiltersContainer.on('click', '.remove-product-category-filter', function(e) {
        var filter = e.currentTarget.closest('.filter-item');
        inactiveFilters.append(createInactiveFilter(filter.dataset['filter'], filter.dataset['count']));

        removeFilter(filter.dataset['filter'], true);
        activeFiltersContainer.trigger('change');
    });

    inactiveFiltersContainer.on('click', '.re-add-product-category-filter', function(e) {
        var filter = e.currentTarget.closest('.filter-item');
        activeFilters.append(createActiveFilter(filter.dataset['filter'], filter.dataset['count']));

        removeFilter(filter.dataset['filter'], false);
        activeFiltersContainer.trigger('change');
    });

    removeAllButton.on('click', function() {
        activeFiltersContainer.find('.remove-product-category-filter').each(function(index, el) {
            el.click()
        });
    });
});

function getAllFilters() {
    return getFilters(activeFilters, true).concat(getFilters(inactiveFilters, false));
}

/**
 *
 * @param selector
 * @param value
 * @returns {Array}
 */
function getFilters(selector, value) {
    var filters = [];
    selector.find('li')
        .each(function(index, el) {
            var filter = {};
            filter[el.dataset['filter']] = value;
            filters.push(filter);
        });

    return filters;
}

function addToActiveList(filterToAdd, count) {
    activeFilters.append(createActiveFilter(filterToAdd, count));
    activeFiltersContainer.trigger('change');
}

function removeFromInactiveList(filter) {
    removeFilter(filter, false);
    activeFiltersContainer.trigger('change');
}

function createActiveFilter(filter, count) {
    return '<li data-count="' + count + '" data-filter="' + filter + '" class="filter-item dd-item">\n' +
        '    <a class="btn btn-xs btn-outline btn-danger remove-product-category-filter" title="Remove Filter">\n' +
        '        <i class="fa fa-fw fa-trash"></i>\n' +
        '    </a>\n' +
        '    <div class="dd-handle extra-padding">\n' +
                  filter + ' (' + count + ')' +
        '    </div>\n' +
        '</li>';
}

function createInactiveFilter(filter, count) {
    return '<li data-count="' + count + '" data-filter="' + filter + '" class="filter-item dd-item">\n' +
        '    <a class="btn btn-xs btn-outline btn-info re-add-product-category-filter" title="Re-add Filter">\n' +
        '        <i class="fa fa-fw fa-plus-circle"></i>\n' +
        '    </a>\n' +
        '     <div class="dd-handle">' + filter + '</div>' +
        '</li>';
}

function removeFilter(filter, active) {
    var selector = activeFilters;
    if (!active) {
        selector = inactiveFilters;
    }

    selector.find('li').each(function(index, el) {
        if(filter === el.dataset['filter']) {
            el.remove();
        }
    });
}

module.exports = {
    getAllFilters: getAllFilters,
    addToActiveList: addToActiveList,
    removeFromInactiveList: removeFromInactiveList
};
