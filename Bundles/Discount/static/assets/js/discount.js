'use strict';

$(function(){

    $('#add-collector-container').click(function(e){
        e.preventDefault();
        loadCollectorPluginForm($(this), 'cart_rule');
    });

    $('#add-collector-pool-container').click(function(e){
        e.preventDefault();
        loadCollectorPluginForm($(this), 'voucher_codes');
    });

    $('#add-rules-container').click(function(e){
        e.preventDefault();
        loadCartRulesForm($(this), 'cart_rule');
    });

    $('#add-rules-pool-container').click(function(e){
        e.preventDefault();
        loadCartRulesForm($(this), 'voucher_codes');
    });

});
