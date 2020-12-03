/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @param {int} idCategoryNode
 * @param {jQuery} targetElement
 * @param {object} progressBar
 *
 * @return {void}
 */
function load(idCategoryNode, targetElement, progressBar)
{
    progressBar.show();

    var url = '/category-gui/tree/?id-root-node=' + idCategoryNode;

    jQuery
        .get(url, jQuery.proxy(function(targetElement, response) {
            targetElement.html(response);
        }, null, targetElement))
        .always(function() {
            progressBar.hide();
        });
}

/**
 * @param {jQuery} targetElement
 */
function reset(targetElement)
{
    targetElement.html('');
}

module.exports = {
    load: load,
    reset: reset
};
