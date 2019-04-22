const defaultEditorConfig = require('ZedGuiEditorConfiguration');
const getEditorConfig = require('./editorComponents/config');
const editorButtons = require('./editorComponents/buttons');
const contentItemDialog = require('./content-item-editor-dialog');

const ContentItemEditor = function(dropdownContentUrl) {
    this.dropDownItems = [];

    this.initialization = function(dropdownItems, initSummernote) {
        this.dropDownItems = dropdownItems;

        contentItemDialog.init();

        if (initSummernote) {
            initSummernote();
        }
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

    this.createDropdownButton = function (dropdownContentUrl) {
        return editorButtons.ContentItemDropdownButton(
            'Add Content <i class="fa fa-caret-down" aria-hidden="true"></i>',
            this.generateDropdownList(),
            this.dropDownClickHandler
        );
    };

    this.generateDropdownList = function () {
        const dropdownList = this.dropDownItems.reduce(function(currentList, dropItem) {
            const dropItemTemplate = '<li role="listitem">' +
                '<a href="#" data-url="' + dropItem.contentListUrl + '">' +
                dropItem.name +
                '</a>' + '</li>';

            return currentList + dropItemTemplate;
        }, '');

        return dropdownList;
    }

    this.dropDownClickHandler = function (context) {
        return context.createInvokeHandler('contentItemDialog.show', event);
    };
}

module.exports = ContentItemEditor;
