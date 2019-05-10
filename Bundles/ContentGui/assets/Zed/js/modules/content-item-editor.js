/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var editorConfig = require('ZedGuiEditorConfiguration');
var editorButtons = require('./editorComponents/buttons');
var ContentItemDialog = require('./content-item-editor-dialog');
var ContentItemPopover = require('./content-item-editor-popover');

var ContentItemEditor = function(options) {
    this.dropDownItems = [];
    this.buttonTitle = 'Insert Content';
    this.title = 'Content';
    this.insertButtonTitle = 'Insert';
    this.dialogContentUrl = '';
    this.popoverButtonsContent = {};
    this.editorContentWidgetTemplate = '';

    $.extend(this, options);

    this.initialization = function() {
        new ContentItemDialog(
            this.title,
            this.dialogContentUrl,
            this.insertButtonTitle,
            this.editorContentWidgetTemplate
        );
        new ContentItemPopover();
    };

    this.getEditorConfig = function (baseConfig = '') {
        baseConfig = editorConfig.getGlobalConfig(baseConfig);

        if (!baseConfig) {
            baseConfig = editorConfig.getConfig();
        }

        var contentGuiConfig = {
            toolbar: [
                ['insert', ['dropdownContentItem']]
            ],
            buttons: {
                dropdownContentItem: this.createDropdownButton(),
                editWidget: this.createEditWidgetButton(),
                editContentItem: this.createEditContentItemButton(),
                removeContentItem: this.createRemoveContentItemButton()
            },
            popover: {
                'editContentItem': ['editWidget', 'editContentItem', 'removeContentItem']
            },
            dialogsInBody: true
        };

        return editorConfig.mergeConfigs(baseConfig, contentGuiConfig);
    };

    this.createDropdownButton = function () {
        return editorButtons.ContentItemDropdownButton(
            this.buttonTitle,
            this.generateDropdownList(),
            this.dropDownClickHandler
        );
    };

    this.createEditWidgetButton = function () {
        return editorButtons.PopoverButton(
            this.popoverButtonsContent.editWidget,
            function () {
                alert('Edit Widget');
            }
        );
    }

    this.createEditContentItemButton = function () {
        return editorButtons.PopoverButton(
            this.popoverButtonsContent.editContentItem,
            function () {
                alert('Edit Content Item');
            }
        );
    }

    this.createRemoveContentItemButton = function () {
        return editorButtons.PopoverButton(
            this.popoverButtonsContent.removeContentItem,
            function () {
                alert('remove Content Item');
            }
        );
    }

    this.generateDropdownList = function () {
        return this.dropDownItems.reduce(function(currentList, dropItem) {
            var dropItemTemplate = '<li role="listitem">' +
                '<a href="#" data-type="' + dropItem.type + '">' +
                dropItem.name +
                '</a>' + '</li>';

            return currentList + dropItemTemplate;
        }, '');
    };

    this.dropDownClickHandler = function (context) {
        return context.createInvokeHandler('contentItemDialog.show');
    };

    this.initialization();
};

module.exports = ContentItemEditor;
