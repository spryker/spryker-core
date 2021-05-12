/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ProductSelector = function ProductSelector(options) {
    this.idProductAbstractElement = null;
    this.selectedProductContainer = null;
    this.selectProductNotice = null;
    this.productTable = null;
    this.selectProductUrl = null;

    $.extend(this, options);

    this.initialiseProductTable();
    this.findSelectedProduct();
};

ProductSelector.prototype.initialiseProductTable = function () {
    var self = this;
    this.productTable.dataTable({
        destroy: true,
        scrollX: 'auto',
        autoWidth: false,
        fnDrawCallback: function (settings) {
            self.onTableDraw(settings);
        },
    });
};

ProductSelector.prototype.onTableDraw = function (settings) {
    var self = this;
    $('a[data-select-product]').each(function (index, element) {
        self.addClickEventToCheckbox($(element));
    });
};

ProductSelector.prototype.findSelectedProduct = function () {
    var idSelectedProduct = parseInt(this.idProductAbstractElement.val());
    if (!idSelectedProduct) {
        return;
    }

    var self = this;
    $.get(this.selectProductUrl + idSelectedProduct).done(function (selectedProduct) {
        self.updateSelectedProduct(selectedProduct);
    });
};

ProductSelector.prototype.addClickEventToCheckbox = function (element) {
    var self = this;
    $(element).on('click', function (event) {
        $.get(self.selectProductUrl + $(event.target).data('select-product')).done(function (selectedProduct) {
            self.updateSelectedProduct(selectedProduct);
        });
    });
};

ProductSelector.prototype.updateSelectedProduct = function (selectedProduct) {
    var name = selectedProduct['spy_product_abstract_localized_attributes.name'];
    var description = selectedProduct['spy_product_abstract_localized_attributes.description'];
    var categories = selectedProduct.assigned_categories;
    var imageUrl = selectedProduct['spy_product_image.external_url_small'];
    var idProductAbstract = selectedProduct['spy_product_abstract.id_product_abstract'];

    this.selectProductNotice.hide();

    this.selectedProductContainer.show();
    this.selectedProductContainer.find('#product-img').attr({ src: imageUrl });
    this.selectedProductContainer.find('.product-name').text(name);
    this.selectedProductContainer.find('#product-description').text(description);
    this.selectedProductContainer.find('#product-category').text(categories);
    this.idProductAbstractElement.val(idProductAbstract);
};

module.exports = ProductSelector;
