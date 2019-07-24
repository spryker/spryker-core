/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var safeChecks = require('ZedGuiModules/libs/safe-checks');

$(document).ready(function() {
    var $nodeTypeField = $('#navigation_node_node_type');

    displaySelectedNodeTypeField($nodeTypeField.val());
    $nodeTypeField.on('change', changeNodeType);

    var validFrom = $('#navigation_node_valid_from');
    var validTo = $('#navigation_node_valid_to');

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

    $('.spryker-form-autocomplete').each(function(key, value) {
        var autoCompletedField = $(value);
        if (autoCompletedField.data('url') === 'undefined') {
            return;
        }

        if (autoCompletedField.hasClass('ui-autocomplete')) {
            autoCompletedField.autocomplete('destroy');
        }

        autoCompletedField.autocomplete({
            source: autoCompletedField.data('url'),
            minLength: 3
        });
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
