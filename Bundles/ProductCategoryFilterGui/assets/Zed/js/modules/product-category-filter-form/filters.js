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
        inactiveFilters.append(createInactiveFilter(filter.dataset['filterKey'], filter.dataset['filterLabel'], filter.classList.contains('non-filter-attribute')));

        removeFilter(filter.dataset['filterKey'], true);
        activeFiltersContainer.trigger('change');
    });

    inactiveFiltersContainer.on('click', '.re-add-product-category-filter', function(e) {
        var filter = e.currentTarget.closest('.filter-item');
        activeFilters.append(createActiveFilter(filter.dataset['filterKey'], filter.dataset['filterLabel'], filter.classList.contains('non-filter-attribute')));

        removeFilter(filter.dataset['filterKey'], false);
        activeFiltersContainer.trigger('change');
    });

    removeAllButton.on('click', function() {
        activeFiltersContainer.find('.remove-product-category-filter').each(function(index, el) {
            el.click()
        });
    });
});

function getAllFilters() {
    return {
        filters: getFilters(activeFilters, true).concat(getFilters(inactiveFilters, false))
    };
}

/**
 *
 * @param selector
 * @param isActive
 * @returns {Array}
 */
function getFilters(selector, isActive) {
    var filters = [];
    selector.find('li')
        .each(function(index, el) {
            filters.push(
                {
                    key: el.dataset['filterKey'],
                    label: el.dataset['filterLabel'],
                    isActive: isActive
                }
            );
        });

    return filters;
}

function addToActiveList(filterToAdd) {
    activeFilters.append(createActiveFilter(filterToAdd, filterToAdd, true));
    activeFiltersContainer.trigger('change');
}

function removeFromInactiveList(filterKey) {
    removeFilter(filterKey, false);
    activeFiltersContainer.trigger('change');
}

function createActiveFilter(filterKey, filterLabel, nonFilterAttribute) {
    return createFilter(filterKey, filterLabel, nonFilterAttribute, 'btn-danger remove-product-category-filter', 'Remove Filter', 'fa-trash');
}

function createInactiveFilter(filterKey, filterLabel, nonFilterAttribute) {
    return createFilter(filterKey, filterLabel, nonFilterAttribute, 'btn-info re-add-product-category-filter', 'Re-add Filter', 'fa-plus-circle');
}

function createFilter(filterKey, filterLabel, nonFilterAttribute, anchorClass, anchorTitle, iconClass) {
    return '<li data-filter-key="' + filterKey + '"  data-filter-label="' + filterLabel + '" class="filter-item dd-item ' + ((nonFilterAttribute)? 'non-filter-attribute': '') + '">\n' +
        '    <a class="btn btn-xs btn-outline ' + anchorClass + '" title="' + anchorTitle + '">\n' +
        '        <i class="fa fa-fw ' + iconClass + '"></i>\n' +
        '    </a>\n' +
        '    <div class="dd-handle">\n' +
        filterLabel +
        '    </div>\n' +
        '</li>';
}

function removeFilter(filterKey, active) {
    var selector = activeFilters;
    if (!active) {
        selector = inactiveFilters;
    }

    selector.find('li').each(function(index, el) {
        if(filterKey === el.dataset['filterKey']) {
            el.remove();
        }
    });
}

module.exports = {
    getAllFilters: getAllFilters,
    addToActiveList: addToActiveList,
    removeFromInactiveList: removeFromInactiveList
};
