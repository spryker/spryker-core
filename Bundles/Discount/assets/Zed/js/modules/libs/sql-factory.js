'use strict';

var SprykerQueryBuilder = require('./spryker-query-builder');

module.exports = function (inputElementId, targetElementId, disableValidation) {
    var inputElement = $(inputElementId);
    $(inputElement).parent().addClass('hidden');

    var options = {
        inputElement: inputElement,
        sqlQuery: inputElement.val(),
        ajaxUrl: inputElement.data('url'),
        label: inputElement.data('label'),
        targetElement: targetElementId,
        disableValidation: disableValidation || false,
    };

    return new SprykerQueryBuilder(options);
};
