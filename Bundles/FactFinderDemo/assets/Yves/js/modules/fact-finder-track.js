/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var $ = require('jquery');

var factFinderTrack = {

    url: '/fact-finder/track?',

    query: function (queryData) {
        if (queryData == '') {
            return false;
        }

        console.log(queryData);

        console.log(this.buildUrl(queryData));

        $.ajax({
            type: 'GET',
            url: this.buildUrl(queryData),
            context: this,
            success: this.handleAjaxResponse
        });
    },

    handleAjaxResponse: function (response) {
        console.log(response);
    },

    buildUrl: function (queryData) {
        var queryString = '';

        $.each(queryData, function(key, value) {
            queryString += key + '=' + value + '&';
        });

        return this.url + queryString;
    }

};

module.exports = factFinderTrack;