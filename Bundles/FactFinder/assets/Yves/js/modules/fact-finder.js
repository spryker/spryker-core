/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var $ = require('jquery');
var factFinderSearch = require('./fact-finder-search');
var suggestionsBox = require('./fact-finder-suggestions-box');

function init(config) {
    $("#ffSortButton").click(function(){
        window.location = $("#ffSortSelect").val();
    });

    $("#ffSearchInput").keyup(function(e){
        switch(e.which) {
            case 38: // up
                suggestionsBox.highlightRow(-1);
                break;

            case 40: // down
                suggestionsBox.highlightRow(1);
                break;

            case 13: // enter
                suggestionsBox.goTo(e);
                break;

            default: factFinderSearch.query($("#ffSearchInput").val());
                break;
        }
    });

    $("#ffSearchInput").click(function(){
        factFinderSearch.query($("#ffSearchInput").val());
    });

    $(document).click(function(){
        suggestionsBox.showSuggestionsBox(false);
    });
}

module.exports = {
    init: init
};
