const editorConfig = require('ZedGuiEditorConfiguration');
const ContentItemEditor = require('./modules/content-item-editor');

const initContentItemEditor = function() {
    if (!editorConfig.globalConfigExist('cms')) {
        return;
    }

    const editor = new ContentItemEditor();

    $('.html-editor[data-editor-config="cms"]').summernote(editor.getContentItemEditorConfig());
};

$(document).ready(function() {
    initContentItemEditor();
});
