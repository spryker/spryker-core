/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

if (!window.ContentProductAbstractList) {
    require('../sass/table.scss');
    require('./modules/add-item-list-table');
    window.ContentProductAbstractList = true;
}
