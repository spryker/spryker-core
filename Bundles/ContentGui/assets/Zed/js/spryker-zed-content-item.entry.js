/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../sass/main.scss');
const ContentItemEditor = require('./modules/content-item-editor');

const editor = new ContentItemEditor(window.editorConfiguration.contentGuiConfigData);
window.editorConfiguration.cms = editor.getEditorConfig('cms');
