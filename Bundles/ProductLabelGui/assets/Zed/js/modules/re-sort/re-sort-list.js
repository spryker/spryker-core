/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $list = null;
var $button = null;

/**
 * @param {string} listSelector
 * @param {string} buttonSelector
 *
 * @return {void}
 */
function initialize(listSelector, buttonSelector) {
    $list = $(listSelector);
    $button = $(buttonSelector);

    initializeDragAndDrop();
    initializeSaveButton();
}

/**
 * @return {void}
 */
function initializeDragAndDrop() {
    $list.nestable({
        maxDepth: 1
    });
}

/**
 * @return {void}
 */
function initializeSaveButton() {
    $button.on('click', function() {
        sendListData();
        disableSaveButton(true);
    });

    disableSaveButton();

    $list.on('change', function() {
        enableSaveButton();
    });
}

/**
 * @param {bool} showLoader
 *
 * @return {void}
 */
function disableSaveButton(showLoader) {
    $button.attr('disabled', '');

    if (showLoader === true) {
        showButtonLoader();
    } else {
        hideButtonLoader();
    }
}

/**
 * @return {void}
 */
function enableSaveButton() {
    $button.removeAttr('disabled');
    hideButtonLoader();
}

/**
 * @return {void}
 */
function showButtonLoader() {
    $button.children('.js-loader').removeClass('wave-loader--hidden');
}

/**
 * @return {void}
 */
function hideButtonLoader() {
    $button.children('.js-loader').addClass('wave-loader--hidden');
}

/**
 * @return {void}
 */
function sendListData() {
    var listData = readCurrentListOrder();

    var promise = $.post(
        '/product-label-gui/re-sort/save',
        {
            'sort-order-data': listData
        }
    );

    promise.done(function(response) {
        window.sweetAlert({
            title: response.success ? "Success" : "Error",
            text: response.message,
            type: response.success ? "success" : "error"
        });
    });

    promise.always(function() {
        disableSaveButton();
    })
}

/**
 * @return {object}
 */
function readCurrentListOrder() {
    var listData = $list.nestable('serialize');
    var productLabelPositions = {};

    $.each(listData, function(index, item) {
        productLabelPositions[item.idProductLabel] = {
            'idProductLabel': item.idProductLabel,
            'position': (index + 1)
        };
    });

    return productLabelPositions;
}

module.exports = {
    initialize: initialize
};
