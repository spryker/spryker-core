'use strict';

var SprykerQueryBuilder = require('./spryker-query-builder');

module.exports = function (inputElementId, targetElementId) {
    var inputElement = $(inputElementId);
    var sqlRules = inputElement.val();
    var ajaxUrl = inputElement.data('url');

    return new SprykerQueryBuilder(sqlRules, ajaxUrl, inputElement, targetElementId);
};
