/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('jquery');
require('jstree');

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
    $list.jstree({
        'plugins': ['types', 'wholerow', 'dnd'],
        'core': {
            'check_callback': true
        },
        'types': {
            'default': {
                'icon': 'fa fa-tag',
                'max_depth': 0
            }
        },
        'dnd': {
            'is_draggable': true,
            'large_drag_target': true,
            'large_drop_target': true
        }
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

    $list.on('move_node.jstree', function() {
        enableSaveButton();
    });
}

function disableSaveButton(showLoader) {
    $button.attr('disabled', '');

    if (showLoader === true) {
        $button.children('.js-loader').show();
    } else {
        $button.children('.js-loader').hide();
    }
}

function enableSaveButton() {
    $button.removeAttr('disabled');
    $button.children('.js-loader').hide();
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
    var listData = $list.jstree(true).get_json();
    var productLabelPositions = {};

    $.each(listData, function(index, item) {
        productLabelPositions[item.data.idProductLabel] = {
            'idProductLabel': item.data.idProductLabel,
            'position': (index + 1)
        };
    });

    return productLabelPositions;
}

module.exports = {
    initialize: initialize
};
