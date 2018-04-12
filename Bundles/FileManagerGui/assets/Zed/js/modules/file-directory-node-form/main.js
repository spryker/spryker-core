/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var safeChecks = require('ZedGuiModules/libs/safe-checks');

$(document).ready(function() {
    var $nodeTypeField = $('#file_directory_node_node_type');

    displaySelectedNodeTypeField($nodeTypeField.val());
    $nodeTypeField.on('change', changeNodeType);

    var validFrom = $('#file_directory_node_valid_from');
    var validTo = $('#file_directory_node_valid_to');

    validFrom.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        maxDate: validTo.val(),
        defaultData: 0,
        onClose: function(selectedDate) {
            validTo.datepicker('option', 'minDate', selectedDate);
        }
    });

    validTo.datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: validFrom.val(),
        onClose: function(selectedDate) {
            validFrom.datepicker('option', 'maxDate', selectedDate);
        }
    });

    safeChecks.addSafeDatetimeCheck();
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
    triggerResize();
}

/**
 * @return {void}
 */
function resetNodeTypeFields() {
    $('.js-node-type-field')
        .addClass('hidden')
        .find('input[type="text"]').val('');
}

/**
 * @return {void}
 */
function triggerResize() { 
    var resizeEvent = new Event('resize');
    window.dispatchEvent(resizeEvent);
}
