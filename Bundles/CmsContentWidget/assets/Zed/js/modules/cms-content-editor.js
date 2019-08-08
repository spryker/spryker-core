/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const editorConfig = require('ZedGuiEditorConfiguration');

/**
 * @param {string} options
 *
 * @constructor
 */
var CmsContentEditor = function CmsContentEditor(options)
{
    this.dropdownItems = [];

    $.extend(this, options);
};

/**
 * @param {string} baseConfig
 *
 * @returns array
 */
CmsContentEditor.prototype.getEditorConfig = function(baseConfig = '')
{
    baseConfig = editorConfig.getGlobalConfig(baseConfig);

    if (!baseConfig) {
        baseConfig = editorConfig.getConfig();
    }

    const cmsContentWidgetConfig = {
        toolbar: [
            ['insert', ['cmsContentWidget']]
        ],
        buttons: {
            cmsContentWidget : this.createCmsContentWidgetButton(this.dropdownItems)
        }
    };

    return editorConfig.mergeConfigs(baseConfig, cmsContentWidgetConfig);
};

/**
 * @param {array} cmsContentWidgetDropDownItems
 *
 * @returns {Function}
 */
CmsContentEditor.prototype.createCmsContentWidgetButton = function (cmsContentWidgetDropDownItems)
{
    return function (context) {
        var ui = $.summernote.ui;

        var button = ui.buttonGroup([
            ui.button({
                contents: 'content widget <i class="fa fa-caret-down" aria-hidden="true"></i>',
                data: {
                    toggle: 'dropdown'
                }
            }),
            ui.dropdown({
                items: cmsContentWidgetDropDownItems,
                callback: function (items) {
                    $(items).find('li a').on('click', function(event) {
                        context.invoke("editor.insertText", " {{ "  + $(this).html() + "(['identifier']) }} ");
                        event.preventDefault();
                    })
                }
            })
        ]);

        return button.render();
    }
};

module.exports = CmsContentEditor;
