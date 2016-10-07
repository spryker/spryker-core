/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var progressBarElements = {
    bar: null,
    inner: null,
};

/**
 * @param {int} idCategoryNode
 * @param {jQuery} targetElement
 *
 * @return {void}
 */
function load(idCategoryNode, targetElement)
{
    showProgressBar();

    var url = '/category/tree/?id-root-node=' + idCategoryNode;

    jQuery
        .get(url, jQuery.proxy(function(targetElement, response) {
            targetElement.html(response);
        }, null, targetElement))
        .always(function() {
            hideProgressBar();
        });
}

/**
 * @param {jQuery} targetElement
 */
function reset(targetElement)
{
    targetElement.html('');
}

/**
 * @return {void}
 */
function showProgressBar()
{
    getProgressBarElements().bar.removeClass('hidden');
    getProgressBarElements().inner.css('width',  '100%');
}

/**
 * @return {void}
 */
function hideProgressBar()
{
    getProgressBarElements().bar.addClass('hidden');
    getProgressBarElements().inner.css('width',  '0');
}

/**
 * @return {object}
 */
function getProgressBarElements()
{
    if (progressBarElements.bar === null) {
        progressBarElements.bar = jQuery('#category-tree-progress-bar');
        progressBarElements.inner = jQuery('.progress-bar', progressBarElements.bar);
    }

    return progressBarElements;
}

module.exports = {
    load: load,
    reset: reset
};
