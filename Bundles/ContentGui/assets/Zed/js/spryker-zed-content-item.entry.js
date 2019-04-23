const ContentItemEditor = require('./modules/content-item-editor');

$(document).ready(function() {
    const editor = new ContentItemEditor();

    $('.html-editor[data-editor-config="cms"]').summernote(editor.getContentItemEditorConfig());
});
