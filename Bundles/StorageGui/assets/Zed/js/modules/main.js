/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

import { Highlight } from './highlight.js';
import { CopyAction } from './copy-action.js';
import { DownloadAction } from './download-action.js';
import 'highlight.js/styles/default.css';
import '../../sass/main.scss';

$(document).ready(function () {
    new Highlight();
    new CopyAction();
    new DownloadAction();

    $('.storage-tooltip').tooltip();
});
