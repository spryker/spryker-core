/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var $ = require('jquery');

var suggestionsBox = {

    allProductsUrl: '/fact-finder?query=',

    prepareSuggestionsBlock: function (objectsList)     {
        this.clearSuggestionsBlock();

        $.each(objectsList, function (i, item) {
            var productTemplateHtml = suggestionsBox.getProductTemplateHtml(item);

            $('.ff-products').append(productTemplateHtml);
        });

        this.setSeeAllProductsLink();
        this.showSuggestionsBox(true);
    },

    getProductTemplateHtml: function (item) {
        var productTemplate = $('#suggestions-box-row').clone();
        var productTemplateHtml = $(productTemplate).prop('innerHTML');

        $.each(item, function (index, value) {
            productTemplateHtml = productTemplateHtml.replace(':' + index, value);
        });

        return productTemplateHtml;
    },

    clearSuggestionsBlock: function () {
        $('.ff-products').html('');
    },

    showSuggestionsBox: function (show) {
        if (show === true) {
            $('.ff-suggestion-box').removeClass('is-hidden');
        } else {
            $('.ff-suggestion-box').addClass('is-hidden');
        }
    },

    setSeeAllProductsLink: function () {
        var searchValue = $('#ffSearchInput').val();
        $('.ff-all-products').attr('href', this.allProductsUrl + searchValue);
    }

};

module.exports = suggestionsBox;