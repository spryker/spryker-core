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

        return editorButtons.ContentItemDropdownButton(
            window.editorConfiguration.cms.buttonTitle,
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
