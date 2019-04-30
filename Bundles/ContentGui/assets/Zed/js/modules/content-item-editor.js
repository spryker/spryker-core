const editorConfig = require('ZedGuiEditorConfiguration');
const editorButtons = require('./editorComponents/buttons');
const ContentItemDialog = require('./content-item-editor-dialog');

const ContentItemEditor = function(options) {
    this.dropDownItems = [];
    this.buttonTitle = 'Insert Content';
    this.title = 'Content';

    $.extend(this, options);

    this.initialization = function() {
        new ContentItemDialog(this.title);
    };

    this.getEditorConfig = function (baseConfig = '') {
        baseConfig = editorConfig.getGlobalConfig(baseConfig);

        if (!baseConfig) {
            baseConfig = editorConfig.getConfig();
        }

        const contentGuiConfig = {
            toolbar: [
                ['insert', ['dropdownContentItem']]
            ],
            buttons: {
                dropdownContentItem: this.createDropdownButton()
            }
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
