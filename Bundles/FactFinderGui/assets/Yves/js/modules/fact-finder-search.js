/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var $ = require('jquery');
var suggestionsBox = require('./fact-finder-suggestions-box');

var factFinderSearch = {

    url: '/fact-finder/search?format=jsonp&query=',
    maxItemsCount: 4,
    queryText: '',

    query: function (queryText) {
        if (queryText == '') {
            return false;
        }
        this.queryText = queryText;

        $.ajax({
            type: 'GET',
            url: this.buildUrl(queryText),
            context: this,
            success: this.handleAjaxResponse
        });
    },

    handleAjaxResponse: function (response) {
        var searchResult = response;

        if (searchResult == undefined) {
            return false;
        }
        var objectsList = this.prepareObjectsList(searchResult);

        if (objectsList.length > 0) {
            suggestionsBox.prepareSuggestionsBlock(objectsList);
        }
    },

    buildUrl: function (queryText) {
        return this.url + queryText;
    },

    prepareObjectsList: function (items) {
        var objectsList = [];
        items = this.getDecreasedItemsList(items);

        $.each(items, function (i, item) {
            objectsList.push({
                'name': factFinderSearch.getHighlited(item.label, factFinderSearch.queryText),
                'url': item.attributes.deeplink,
                'image': item.imageUrl
            });
        });

        return objectsList;
    },

    getDecreasedItemsList: function (items) {
        if (items.length > this.maxItemsCount) {
            items.length = this.maxItemsCount;
        }

        return items;
    },

    getHighlited: function (string, substring) {
        var lowerString = string.toLowerCase();
        var lowerSubstring = substring.toLowerCase();

        if (lowerString.indexOf(lowerSubstring) >= 0) {
            var startIndex = lowerString.indexOf(lowerSubstring);
            var realSubstring = string.substr(startIndex, substring.length);

            return string.replace(realSubstring, '<strong>' + realSubstring + '</strong>');
        }

        return string;
    }

};

module.exports = factFinderSearch;