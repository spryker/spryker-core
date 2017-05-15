/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('jquery');

var $abstractProductIdsCsvFormField = null;
var abstractProductIds = null;

/**
 * @param {string} tableSelector
 * @param {string} checkboxSelector
 * @param {string} formFieldSelector
 *
 * @return {void}
 */
function initialize(tableSelector, checkboxSelector, formFieldSelector) {
    $abstractProductIdsCsvFormField = $(formFieldSelector);

    initializeAbstractProductIdList();
    initializeDataTable(tableSelector, checkboxSelector);
}

/**
 * @return {void}
 */
function initializeAbstractProductIdList() {
    abstractProductIds = [];
    var idsCsv = $abstractProductIdsCsvFormField.val();
    var ids = idsCsv.split(',');

    $.each(ids, function(index, id) {
        if (id === '') {
            return;
        }

        var integerId = parseInt(id, 10);
        abstractProductIds.push(integerId);
    })
}

/**
 * @param {string} tableSelector
 * @param {string} checkboxSelector
 *
 * @return {void}
 */
function initializeDataTable(tableSelector, checkboxSelector) {
    var dataTable = $(tableSelector).DataTable();

    dataTable.on('draw', function() {
        initializeCheckboxes(checkboxSelector);
    });
    dataTable.on('search', function() {
        initializeCheckboxes(checkboxSelector);
    });
}

/**
 * @param {string} checkboxSelector
 *
 * @return {void}
 */
function initializeCheckboxes(checkboxSelector) {
    $(checkboxSelector).each(function(index, checkboxNode) {
        var $checkbox = $(checkboxNode);

        updateCheckboxState($checkbox);
        registerCheckboxListener($checkbox);
    });
}

/**
 * @param {jQuery} $checkbox
 *
 * @return {void}
 */
function updateCheckboxState($checkbox) {
    $checkbox.prop('checked', isIdInAbstractProductsList($checkbox.val()));
}

/**
 * @param {jQuery} $checkbox
 *
 * @return {void}
 */
function registerCheckboxListener($checkbox) {
    $checkbox.on('change', checkboxOnClickCallback);
}

/**
 * @param {jQuery.Event} event
 *
 * @return {void}
 */
function checkboxOnClickCallback(event) {
    var $checkbox = $(event.currentTarget);

    if ($checkbox.prop('checked')) {
        addAbstractProductId($checkbox.val());
    } else {
        removeAbstractProductId($checkbox.val());
    }

    writeListOfAbstractProductIds();
}

/**
 * @param {integer|string} id
 *
 * @return {boolean}
 */
function isIdInAbstractProductsList(id) {
    var integerId = parseInt(id, 10);

    return ($.inArray(integerId, abstractProductIds) !== -1);
}

/**
 * @param {string|integer} id
 *
 * @return {void}
 */
function addAbstractProductId(id) {
    var integerId = parseInt(id, 10);

    if (isIdInAbstractProductsList(integerId)) {
        return;
    }

    abstractProductIds.push(integerId);
}

/**
 * @param {integer} id
 *
 * @return {void}
 */
function removeAbstractProductId(id) {
    var integerId = parseInt(id, 10);
    var index = $.inArray(integerId, abstractProductIds);

    if (index === -1) {
        return;
    }

    abstractProductIds.splice(index, 1);
}

/**
 * @return {void}
 */
function writeListOfAbstractProductIds() {
    $abstractProductIdsCsvFormField.val(abstractProductIds.join(','));
}

module.exports = {
    initialize: initialize
};
