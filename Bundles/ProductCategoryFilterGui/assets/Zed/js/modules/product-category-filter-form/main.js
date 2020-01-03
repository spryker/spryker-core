/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var filters = require('./filters');
var idCategory = $('#idCategory').val();
var addButton = $('#addButton');
var saveButton = $('#product-category-filter-save-btn');
var resetButton = $('#reset-filters');
var filterTextField = $('#product_category_filter_filter-autocomplete');

$(document).ready(function() {
    addButton.on('click', function() {
        var filterToAdd = filterTextField.val();
        var filterObject = filters.getAllFilters().filters.find(function(element) {
          return element.key === filterToAdd;
        });
        if (filterObject) {
            if(filterObject.isActive === true) {
                return window.sweetAlert({
                    title: 'Error',
                    text: 'Filter "'+ filterToAdd +'" already defined',
                    html: false,
                    type: 'error'
                });
            }
            filters.removeFromInactiveList(filterToAdd);
        }
        filters.addToActiveList(filterToAdd);

        filterTextField.val('');
        toggleDisableOnAddButton();
    });

    saveButton.on('click', function() {
        var event = new CustomEvent(
            'categoryChanged',
            { detail: { idCategory: idCategory }}
        );
        window.parent.document.dispatchEvent(event);
    });

    resetButton.one('click', function(e) {
        e.preventDefault();
        $(this).addClass('disabled');

        var event = new CustomEvent(
            'resetCategory',
            { detail: { idCategory: idCategory }}
        );

        window.parent.document.dispatchEvent(event);
        window.location.href = e.target.href;
    });

    $('.spryker-form-autocomplete').each(function(key, value) {
        var obj = $(value);
        if (obj.data('url') === 'undefined') {
            return;
        }

        if (obj.hasClass('ui-autocomplete')) {
            obj.autocomplete('destroy');
        }

        obj.autocomplete({
            source: function(request, response) {
                $.get(
                    obj.data('url'),
                    { term: request.term, category: idCategory },
                    function(data) {
                        return response(data);
                    }
                );
            },
            minLength: 3,
            select: function() {
                toggleDisableOnAddButton();
            }
        });
    });
});


function toggleDisableOnAddButton() {
    addButton.prop('disabled', function () {
        return ! $(this).prop('disabled');
    });
}
