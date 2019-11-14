/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

import { applyPolyfills, defineCustomElements } from '@spryker/backoffice-ui/backoffice/loader/index.mjs';

applyPolyfills().then(() => {
    defineCustomElements(window);
});
