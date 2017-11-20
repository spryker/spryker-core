/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var filters = require('./filters');
var idCategory = $('#idCategory').val();
var addButton = $('#addButton');
var filterTextField = $('#product_category_filter');

$(document).ready(function() {
    addButton.on('click', function() {
        var currentList = JSON.parse(filters.getCurrentList());
        var productCountRegex = /\s*\((\d+)\)/;
        var productCount = productCountRegex.exec(filterTextField.val())[1];
        var filter = filterTextField.val().replace(productCountRegex, '');
        if($.inArray(filter, currentList) !== -1) {
            alert('Filter "'+ filter +'" already defined');
        } else {
            filters.addToList(filter, productCount);
        }

        toggleDisableOnAddButton();
    });

    $('.spryker-form-autocomplete').each(function(key, value) {
        var obj = $(value);
        if (obj.data('url') === 'undefined') {
            return;
        }

        obj.autocomplete('destroy');
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
            select: function(event, ui) {
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
