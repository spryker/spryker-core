/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var filterList = {};

var showLoaderBar = function(btn){
    $('.progress').removeClass('hidden');
    $(btn).addClass('hidden');
};

var closeLoaderBar = function(btn){
    $('.progress').addClass('hidden');
    $(btn).removeClass('hidden');
};

$(document).ready(function() {

    /**
     * Save filter order on click
     */
    $('#save-filter-order').on('click', function() {
        var button = this;
        showLoaderBar(button);

        $.ajax('/product-search/filter-reorder/save', {
            method: 'POST',
            data: {
                'filter_list': filterList
            },
            complete: function() {
                closeLoaderBar(button);
            },
            success: function(response) {
                swal({
                    title: "Success",
                    text: response.message,
                    type: "success"
                });
            },
            error: function(response) {
                swal({
                    title: "Error",
                    text: response.message,
                    type: "error"
                });
            },
        });
    });

    $('#filter-container').nestable({
        group: 1,
        maxDepth: 1
    }).on('change', function(e) {
        var list = e.length ? e : $(e.target);
        filterList = list.nestable('serialize');
    });

});
