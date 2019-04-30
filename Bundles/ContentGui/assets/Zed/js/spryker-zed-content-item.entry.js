require('../sass/main.scss');
const ContentItemEditor = require('./modules/content-item-editor');

const editor = new ContentItemEditor(window.editorConfiguration.contentGuiConfigData);
window.editorConfiguration.cms = editor.getEditorConfig('cms');
