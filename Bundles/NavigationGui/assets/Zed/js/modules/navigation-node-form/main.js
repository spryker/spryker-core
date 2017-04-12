/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

$(document).ready(function() {
    var $nodeTypeField = $('#navigation_node_node_type');

    displaySelectedNodeTypeField($nodeTypeField.val());
    $nodeTypeField.on('change', changeNodeType);
});

/**
 * @param {string} type
 *
 * @return {void}
 */
function displaySelectedNodeTypeField(type) {
    $('[data-node-type="' + type + '"]').removeClass('hidden');
}

/**
 * @return {void}
 */
function changeNodeType() {
    resetNodeTypeFields();
    displaySelectedNodeTypeField($(this).val());
}

/**
 * @return {void}
 */
function resetNodeTypeFields() {
    $('.js-node-type-field')
        .addClass('hidden')
        .find('input[type="text"]').val('');
}
