/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @type {object}
 */
var domElements = {
    wrapper: null,
    bar: null
};

var selector = '#progress-bar';

/**
 * @param {string} progressBarSelector
 *
 * @return {void}
 */
function setSelector(progressBarSelector)
{
    selector = progressBarSelector;
}

/**
 * @return {void}
 */
function show()
{
    getDomElements().wrapper.removeClass('hidden');
    getDomElements().bar.css('width', '100%');
}

/**
 * @return {void}
 */
function hide()
{
    getDomElements().bar.css('width', 0);
    getDomElements().wrapper.addClass('hidden');
}

/**
 *
 * @return {object}
 */
function getDomElements()
{
    if (domElements.wrapper === null) {
        domElements.wrapper = jQuery(selector);
        domElements.bar = domElements.wrapper.children('.progress-bar');
    }

    return domElements;
}

module.exports = {
    setSelector: setSelector,
    show: show,
    hide: hide
};
