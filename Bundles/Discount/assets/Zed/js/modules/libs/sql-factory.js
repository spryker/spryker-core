'use strict';

var SprykerQueryBuilder = require('./spryker-query-builder');

module.exports = function (inputElementId, targetElementId) {
    var inputElement = $(inputElementId);
    $(inputElement).parent().addClass('hidden');

    var options = {
        inputElement: inputElement,
        sqlQuery: inputElement.val(),
        ajaxUrl: inputElement.data('url'),
        label: inputElement.data('label'),
        targetElement: targetElementId
    };

    return new SprykerQueryBuilder(options);
};
