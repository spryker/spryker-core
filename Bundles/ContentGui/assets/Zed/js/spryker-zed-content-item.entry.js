const ContentItemEditor = require('./modules/content-item-editor');

$(document).ready(function() {
    const requestUrl = window.location.origin + '/content-gui/configuration/editor-content-list-json';
    const editor = new ContentItemEditor();

    $.ajax({
        type: 'GET',
        url: requestUrl,
        context: this,
        success: function(dropdownItems) {
            editor.initialization(
                dropdownItems,
                function () {
                    $('.html-editor[data-editor-config="contentItem"]').summernote(editor.getContentItemEditorConfig());
                }
            );
        }
    });
});
