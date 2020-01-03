/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const CmsContentEditor = require('./cms-content-editor');

const editor = new CmsContentEditor(window.editorConfiguration.cmsContentWidgetConfigData);
window.editorConfiguration.cms = editor.getEditorConfig('cms');
