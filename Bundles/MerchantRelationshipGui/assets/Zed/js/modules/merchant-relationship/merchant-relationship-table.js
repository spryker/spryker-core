/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

var merchantRelationshipTable;

/**
 * @param {string} selector - jQuery selector.
 */
function initialize(selector) {
    merchantRelationshipTable = $(selector).DataTable();
    $('#company-select').on('change', changeCompany);
}

/**
 * @param {Event} e
 *
 * @return {void}
 */
function changeCompany(e) {
    var companyId = $(this)[0].value;
    window.location = createListUrl(companyId);
}

/**
 * @param {string} companyId
 *
 * @return {string}
 */
function createListUrl(companyId) {
    var parameters = {};
    if (companyId.length > 0) {
        parameters['id-company'] = companyId;
    }
    var finalUrl = window.location.pathname + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}
/**
 * Open public methods
 */
module.exports = {
    initialize: initialize
};
