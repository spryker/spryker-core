/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function WarehouseIdSelector() {
    this.selectedIds = {};

    this.addIdToSelection = (id) => {
        this.selectedIds[id] = id;
    };

    this.removeIdFromSelection = (id) => {
        delete this.selectedIds[id];
    };

    this.isIdSelected = (id) => this.selectedIds.hasOwnProperty(id);

    this.getSelectedIds = () => this.selectedIds;
}

module.exports = WarehouseIdSelector;
