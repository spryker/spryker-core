/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var editorConfig = require('ZedGuiEditorConfiguration');
var editorButtons = require('./editorComponents/buttons');
var ContentItemDialog = require('./content-item-editor-dialog');

var ContentItemEditor = function(options) {
    this.dropDownItems = [];
    this.buttonTitle = 'Insert Content';
    this.title = 'Content';
    this.insertButtonTitle = 'Insert';
    this.dialogContentUrl = '';

    $.extend(this, options);

    this.initialization = function() {
        new ContentItemDialog(this.title, this.dialogContentUrl, this.insertButtonTitle);
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
                dropdownContentItem: this.createDropdownButton()
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
