/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function CustomerIdSelector() {
    var selector = {};
    var selectedIds = {};
    var idKey = 'id';

    selector.addIdToSelection = function (id) {
        selectedIds[id] = id;
    };

    selector.removeIdFromSelection = function (id) {
        delete selectedIds[id];
    };

    selector.isIdSelected = function (id) {
        return selectedIds.hasOwnProperty(id);
    };

    selector.clearAllSelections = function () {
        selectedIds = {};
    };

    selector.addAllToSelection = function (data) {
        for (var i = 0; i < data.length; i++) {
            var id = data[i][idKey];
            selectedIds[id] = id;
        }
    };

    selector.getSelected = function () {
        return selectedIds;
    };

    return selector;
}

module.exports = {
    /**
     * @return {CustomerIdSelector}
     */
    create: function () {
        return new CustomerIdSelector();
    },
};
