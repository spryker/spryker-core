/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var fileType = {
    storage: [],

    updateStorage: function (id, state) {
        this.storage[id] = state;
    },

    syncStorage: function ($tableBody) {
        if (!this.storage.length) {
            return;
        }

        this.storage.map(function (state, id) {
            var $checkbox = $('#file_type_is_allowed_' + id);

            if ($checkbox) {
                $checkbox.prop('checked', state);
            }
        });
    }
};

$(document).ready(function () {
    var $dataTableBody = $('.dataTable > tbody');

    $dataTableBody.on('DOMSubtreeModified', function () {
        fileType.syncStorage($(this));
    });

    $dataTableBody.on('change', 'input.file_type_is_allowed', function () {
        fileType.updateStorage(
            $(this).attr('data-id'),
            $(this).is(':checked')
        );
    });
});