/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 *
 * @param {string} serializedNodeList
 *
 * @return {void}
 */
function save(serializedNodeList, progressBar) {
    progressBar.show();

    var csrfToken = document.getElementById('category-nodes-re-sort-token').value;
    var promise = jQuery.post('/category/re-sort/save', {
        nodes: serializedNodeList,
        token: csrfToken,
    });

    promise.done(function (response) {
        if (response.code === 200) {
            window.sweetAlert({
                title: 'Success',
                text: response.message,
                type: 'success',
            });
            return true;
        }

        window.sweetAlert({
            title: 'Error',
            text: response.message,
            type: 'error',
        });
    });

    promise.fail(function (xhr, statusMessage, errorMessage) {
        window.sweetAlert({
            title: 'Error',
            text: errorMessage,
            type: 'error',
        });
    });

    promise.always(function () {
        progressBar.hide();
    });
}

module.exports = {
    save: save,
};
