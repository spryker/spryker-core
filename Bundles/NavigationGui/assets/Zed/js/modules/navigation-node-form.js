/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

// TODO: Clean up JS code

$(document).ready(function() {
    var $nodeTypeField = $('#navigation_node_node_type');

    displaySelectedNodeTypeField($nodeTypeField.val());

    $nodeTypeField.on('change', function() {
        // reset node type fields
        $('.js-node-type-field').addClass('hidden');
        $('.js-node-type-field').find('input[type="text"]').val('');

        // show selected
        var type = $(this).val();
        displaySelectedNodeTypeField(type);
    });
});

/**
 * @param {string} type
 *
 * @return {void}
 */
function displaySelectedNodeTypeField(type) {
    $('[data-node-type="' + type + '"]').removeClass('hidden');
}
