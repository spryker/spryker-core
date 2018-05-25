/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var mimeType = {
    storage: [],
    $storageInput: $('#mime_type_settings_form_mimeTypes'),

    updateStorage: function (idMimeType, isAllowed) {
        let index = this.getStorageElementIndexByIdMimeType(idMimeType);

        index === -1 ?
            this.storage.push({idMimeType, isAllowed}) :
            this.storage[index].isAllowed = isAllowed;

        this.$storageInput.val(
            JSON.stringify(this.storage)
        );
    },

    getStorageElementIndexByIdMimeType: function (idMimeType) {
        var filtered = this.storage.filter(function (object) {
            return object.idMimeType === idMimeType;
        });

        return filtered.length === 1 ?
            this.storage.indexOf(filtered[0]) :
            -1;
    },

    syncWithStorage: function ($tableBody) {
        if (!this.storage.length) {
            return;
        }

        this.storage.map(function (mimeType) {
            var $checkbox = $('#mime_type_is_allowed_' + mimeType.idMimeType);

            if ($checkbox) {
                $checkbox.prop('checked', mimeType.isAllowed);
            }
        });
    },
};

$(document).ready(function () {
    var $dataTableBody = $('.dataTable > tbody');

    $dataTableBody.on('DOMSubtreeModified', function () {
        mimeType.syncWithStorage($(this));
    });

    $dataTableBody.on('change', 'input.mime_type_is_allowed', function () {
        mimeType.updateStorage(
            $(this).attr('data-id'),
            $(this).is(':checked')
        );
    });
});