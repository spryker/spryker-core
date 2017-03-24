/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ProductSelector = function ProductSelector(options)
{
    this.idProductAbstractElement = null;
    this.selectedProductContainer = null;
    this.selectProductNotice = null;
    this.productTable = null;

    $.extend(this, options);

    this.initialiseProductTable();
};

ProductSelector.prototype.initialiseProductTable = function ()
{
    var self = this;
    this.productTable.dataTable({
        destroy: true,
        scrollX: 'auto',
        autoWidth: false,
        fnDrawCallback: function(settings) {
            self.onTableDraw(settings);
        }
    });
};

ProductSelector.prototype.onTableDraw = function(settings)
{
    var self = this;
    $('a[data-select-product]').each(function(index, element) {
        self.addClickEventToCheckbox($(element));
    });

    var selectedProduct = this.findSelectedProduct(settings);
    if (!selectedProduct) {
        return;
    }

    this.updateSelectedProduct(selectedProduct);
};

ProductSelector.prototype.findSelectedProduct = function(settings)
{
    var idSelectedProduct = parseInt(this.idProductAbstractElement.val());
    if (!idSelectedProduct) {
        return;
    }

    for (var i = 0; i < settings.json.data.length; i++) {
        var product = settings.json.data[i];
        var idProduct = parseInt(product[0]);

        var selectElement = jQuery.parseHTML(product[5]);
        var rowData = $.parseJSON($(selectElement).attr('data-row'));

        if (idProduct === idSelectedProduct) {
            return rowData;
        }
    }
};

ProductSelector.prototype.addClickEventToCheckbox = function(element)
{
    var self = this;
    $(element).on('click', function(event) {

        var selectedProduct = $.parseJSON($(event.target).attr('data-row'));

        self.updateSelectedProduct(selectedProduct);

        self.selectProductNotice.hide();
        self.idProductAbstractElement.val(selectedProduct['spy_product_abstract.id_product_abstract']);
    });
};

ProductSelector.prototype.updateSelectedProduct = function (selectedProduct)
{
    var name = selectedProduct['spy_product_abstract_localized_attributes.name'];
    var description = selectedProduct['spy_product_abstract_localized_attributes.description'];
    var categories = selectedProduct['assignedCategories'];

    this.selectProductNotice.hide();

    this.selectedProductContainer.show();
    this.selectedProductContainer.find('.product-name').text(name);
    this.selectedProductContainer.find('#product-description').text(description);
    this.selectedProductContainer.find('#product-category').text(categories);
};

module.exports = ProductSelector;
