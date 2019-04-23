const ContentItemEditor = require('./modules/content-item-editor');

$(document).ready(function() {
    const editor = new ContentItemEditor();

    $('.html-editor[data-editor-config="contentItem"]').summernote(editor.getContentItemEditorConfig());
});
