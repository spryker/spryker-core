/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function ProductSelector() {
    var productSelector = {};
    var selectedProducts = {};
    var idKey = 'id';

    productSelector.addProductToSelection = function (idProduct) {
        selectedProducts[idProduct] = idProduct;
    };

    productSelector.removeProductFromSelection = function (idProduct) {
        delete selectedProducts[idProduct];
    };

    productSelector.isProductSelected = function (idProduct) {
        return selectedProducts.hasOwnProperty(idProduct);
    };

    productSelector.clearAllSelections = function () {
        selectedProducts = {};
    };

    productSelector.addAllToSelection = function (data) {
        for (var i = 0; i < data.length; i++) {
            var id = data[i][idKey];
            selectedProducts[id] = id;
        }
    };

    productSelector.getSelected = function () {
        return selectedProducts;
    };

    return productSelector;
}

module.exports = {
    /**
     * @return {ProductSelector}
     */
    create: function () {
        return new ProductSelector();
    },
};
