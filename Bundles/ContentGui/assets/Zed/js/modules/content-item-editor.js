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
    var self = this;
    this.dropDownItems = [];
    this.buttonTitle = 'Insert Content';
    this.title = 'Content';
    this.insertButtonTitle = 'Insert';
    this.dialogContentUrl = '';
    this.popoverButtons = {};
    this.editorContentWidgetTemplate = '';

    $.extend(this, options);

    this.initialization = function() {
        new ContentItemDialog(
            this.title,
            this.dialogContentUrl,
            this.insertButtonTitle,
            this.editorContentWidgetTemplate,
            this.maxWidgetNumber,
        );
        new ContentItemPopover();
    };

    this.getEditorConfig = function (baseConfig = '') {
        var self = this;
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
            callbacks: {
                onKeydown: this.onKeydownHandler,
                onChange: function () {
                    self.onChangeHandler($(this), self);
                }
            },
            dialogsInBody: true
        };

        return editorConfig.mergeConfigs(baseConfig, contentGuiConfig);
    };

    this.createDropdownButton = function () {
        return editorButtons.ContentItemDropdownButton(
            this.buttonTitle,
            this.generateDropdownList(),
            this.showDialogHandler
        );
    };

    this.createEditWidgetButton = function () {
        return editorButtons.PopoverButton(
            this.popoverButtons.editWidget,
            this.showDialogHandler
        );
    };

    this.createEditContentItemButton = function () {
        return editorButtons.PopoverButton(
            this.popoverButtons.editContentItem,
            this.editContentItemHandler
        );
    };

    this.createRemoveContentItemButton = function () {
        return editorButtons.PopoverButton(
            this.popoverButtons.removeContentItem,
            this.removeContentItemHandler
        );
    };

    this.showDialogHandler = function (context) {
        return context.createInvokeHandler('contentItemDialog.show');
    };

    this.editContentItemHandler = function () {
        return function(event) {
            var contentItemId = event.currentTarget.dataset.id;
            var originLink = window.location.origin;

            window.open(originLink + self.contentItemUrl + '?id-content=' + contentItemId, '_blank');
        }
    };

    this.removeContentItemHandler = function (context) {
        return function () {
            context.invoke('contentItemDialog.removeItemFromEditor');
        }
    };

    this.onKeydownHandler = function (event) {
        var pressedKey = event.originalEvent.key;

        if (pressedKey !== 'Enter') {
            return;
        }

        var $editor = $(this);
        var $editorRange = $editor.summernote('editor.createRange');
        var widgetClassName = 'js-content-item-editor';
        var isWidgetWrapper = $($editorRange.sc).find('.' + widgetClassName).length;
        var isWidget = $($editorRange.sc).hasClass(widgetClassName);

        var addNewLineAfterWidget = function (target) {
            event.preventDefault();
            $('<p><br></p>').insertAfter($(target));
            var range = document.createRange();
            range.selectNode(target.nextElementSibling.childNodes[0]);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        };

        if (isWidgetWrapper) {
            addNewLineAfterWidget($editorRange.sc);
        }

        if (isWidget) {
            addNewLineAfterWidget($editorRange.sc.parentNode);
        }

    };

    this.onChangeHandler = function ($editor, self) {
        var curlyBracesRegExp = /\{\{\s*((?!\}\}).)*\s*\}\}/;
        var $editorRange = $editor.summernote('createRange');
        var $editorNode = $($editorRange.sc);
        var nodeContent = $editorNode.text();
        var hasCurlyBraces = curlyBracesRegExp.test(nodeContent);

        if (!hasCurlyBraces) {
            return;
        }

        var $editorParentNode = $editorNode.parents('p');

        self.changeEditorNode($editorParentNode);
    };

    this.changeEditorNode = function ($editorParentNode) {
        if (!$editorParentNode.is('p')) {
            return;
        }

        var $elementForInsert = $(
            '<div>' +
            $editorParentNode.html() +
            '</div>'
        );

        $editorParentNode.replaceWith($elementForInsert);
        this.putCaretAtTheLineEnd($elementForInsert);
    };

    this.putCaretAtTheLineEnd = function ($insertedElement) {
        var range = document.createRange();
        range.selectNode($insertedElement[0].childNodes[0]);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        range.collapse(false);
    };

    this.generateDropdownList = function () {
        return this.dropDownItems.reduce(function(currentList, dropItem) {
            var dropItemTemplate = '<li role="listitem">' +
                '<a href="#" data-type="' + dropItem.type + '" data-new="true">' +
                dropItem.name +
                '</a>' + '</li>';

            return currentList + dropItemTemplate;
        }, '');
    };

    this.initialization();
};

module.exports = ContentItemEditor;
