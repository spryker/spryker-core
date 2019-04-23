const defaultEditorConfig = require('ZedGuiEditorConfiguration');
const getEditorConfig = require('./editorComponents/config');
const editorButtons = require('./editorComponents/buttons');
const contentItemDialog = require('./content-item-editor-dialog');

const ContentItemEditor = function() {
    this.initialization = function() {
        this.dropDownItems = window.editorConfiguration.cms.dropdownItems;
        contentItemDialog.init();
    };

    this.getContentItemEditorConfig = function () {
        const defaultConfig = defaultEditorConfig.getConfig();
        const newConfig = {
            toolbar: [
                ['insert', ['dropdownContentItem']]
            ],
            buttons: {
                dropdownContentItem: this.createDropdownButton()
            }
        };

        return getEditorConfig(defaultConfig, newConfig);
    };

    this.createDropdownButton = function () {
        const buttonContents = window.editorConfiguration.cms.buttonTitle + ' <i class="fa fa-caret-down" aria-hidden="true"></i>';

        return editorButtons.ContentItemDropdownButton(
            buttonContents,
            this.generateDropdownList(),
            this.dropDownClickHandler
        );
    };

    this.generateDropdownList = function () {
        return this.dropDownItems.reduce(function(currentList, dropItem) {
            const dropItemTemplate = '<li role="listitem">' +
                '<a href="#" data-url="' + dropItem.contentListUrl + '">' +
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
