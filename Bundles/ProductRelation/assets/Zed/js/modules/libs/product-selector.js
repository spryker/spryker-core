/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

let ProductSelector = function ProductSelector(options)
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
    let self = this;
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
    let self = this;
    $('a[data-select-product]').each(function(index, element) {
        self.addClickEventToCheckbox($(element));
    });

    let selectedProduct = this.findSelectedProduct(settings);
    if (!selectedProduct) {
        return;
    }

    this.updateSelectedProduct(selectedProduct);
};

ProductSelector.prototype.findSelectedProduct = function(settings)
{
    let idSelectedProduct = this.idProductAbstractElement.val();
    if (!idSelectedProduct) {
        return;
    }

    for (let i = 0; i < settings.json.data.length; i++) {
        let product = settings.json.data[i];
        let idProduct = parseInt(product[0]);

        let selectElement = jQuery.parseHTML(product[5]);
        let rowData = $.parseJSON($(selectElement).attr('data-row'));

        if (idProduct == idSelectedProduct) {
            return rowData;
        }
    }
};

ProductSelector.prototype.addClickEventToCheckbox = function(element)
{
    let self = this;
    $(element).on('click', function(event) {

        let selectedProduct = $.parseJSON($(event.target).attr('data-row'));

        self.updateSelectedProduct(selectedProduct);

        self.selectProductNotice.hide();
        self.idProductAbstractElement.val(selectedProduct['spy_product_abstract.id_product_abstract']);
    });
};

ProductSelector.prototype.updateSelectedProduct = function (selectedProduct)
{
    let name = selectedProduct['spy_product_abstract_localized_attributes.name'];
    let description = selectedProduct['spy_product_abstract_localized_attributes.description'];
    let categories = selectedProduct['assignedCategories'];

    this.selectProductNotice.hide();

    this.selectedProductContainer.show();
    this.selectedProductContainer.find('.product-name').text(name);
    this.selectedProductContainer.find('#product-description').text(description);
    this.selectedProductContainer.find('#product-category').text(categories);
};

module.exports = ProductSelector;
