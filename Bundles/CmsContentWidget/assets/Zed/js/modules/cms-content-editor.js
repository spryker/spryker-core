/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @param {string} options
 *
 * @constructor
 */
var CmsContentEditor = function CmsContentEditor(options)
{
    this.contentWidgetConfigurationProviderUrl = '/cms-content-widget/usage-information/json';
    this.editorClass = '.html-editor';

    $(this.editorClass).each(function(index, element) {
        $(element).summernote('destroy');
    });

    $.extend(this, options);

    this.initialise();
}

/**
 * @return void
 */
CmsContentEditor.prototype.initialise = function ()
{
    var self = this;
    $.ajax({
        type: 'GET',
        url: this.contentWidgetConfigurationProviderUrl,
        context: this,
        success: function(jsonData) {
            $(self.editorClass).summernote(self.getEditorConfig(jsonData));
        }
    });
}

/**
 * @param {string} jsonData
 *
 * @returns array
 */
CmsContentEditor.prototype.getEditorConfig = function(jsonData)
{
    var cmsContentWidgetDropDownItems = this.mapDataForDropdown(jsonData);

    return {
        height: 300,
        maxHeight: 600,
        focus: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['picture', 'link', 'video', 'table', 'hr']],
            ['misc', ['undo', 'redo', 'codeview']],
            ['custom', ['cmsContentWidget']]
        ],
        buttons: {
            'cmsContentWidget' : this.createCmsContentWidgetButton(cmsContentWidgetDropDownItems)
        }
    };
}

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
}

/**
 * @param {string} jsonResponse
 *
 * @returns {Array}
 */
CmsContentEditor.prototype.mapDataForDropdown = function (jsonResponse)
{
    var cmsContentWidgets = JSON.parse(jsonResponse);

    var cmsContentWidgetDropDownItems = [];
    cmsContentWidgets.cms_content_widget_configuration_list.forEach(function(element) {
        cmsContentWidgetDropDownItems.push(element.function_name);
    });

    return cmsContentWidgetDropDownItems;
}

module.exports = CmsContentEditor;
