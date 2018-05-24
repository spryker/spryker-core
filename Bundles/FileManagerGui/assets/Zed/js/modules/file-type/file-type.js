/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var fileType = {
    storage: [],
    $storageInput: $('#file_type_form_fileTypes'),

    updateStorage: function (idFileType, isAllowed) {
        let index = this.getStorageElementIndexByIdFileType(idFileType);

        index === -1 ?
            this.storage.push({idFileType, isAllowed}) :
            this.storage[index].isAllowed = isAllowed;

        this.$storageInput.val(
            JSON.stringify(this.storage)
        );
    },

    getStorageElementIndexByIdFileType: function (idFileType) {
        var filtered = this.storage.filter(function (object) {
            return object.idFileType === idFileType;
        });

        return filtered.length === 1 ?
            this.storage.indexOf(filtered[0]) :
            -1;
    },

    syncWithStorage: function ($tableBody) {
        if (!this.storage.length) {
            return;
        }

        this.storage.map(function (fileType) {
            var $checkbox = $('#file_type_is_allowed_' + fileType.idFileType);

            if ($checkbox) {
                $checkbox.prop('checked', fileType.isAllowed);
            }
        });
    },
};

$(document).ready(function () {
    var $dataTableBody = $('.dataTable > tbody');

    $dataTableBody.on('DOMSubtreeModified', function () {
        fileType.syncWithStorage($(this));
    });

    $dataTableBody.on('change', 'input.file_type_is_allowed', function () {
        fileType.updateStorage(
            $(this).attr('data-id'),
            $(this).is(':checked')
        );
    });
});