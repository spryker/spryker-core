'use strict';

$(function(){

    SprykerAjax.prototype.loadDecisionRulesOptions = function(){
        var elementsCount = $('#rules-container > .col-md-6').length;
        var nextElementIndex = elementsCount + 1;
        this.setUrl('/discount/cart-rule/decision-rule/').setDataType('html').ajaxSubmit({
            elements: nextElementIndex
        }, 'displayNewDecisionRulesOptions', {
            elementIndex: nextElementIndex
        });
    };

    SprykerAjax.prototype.loadDecisionRulesPoolOptions = function(){
        var elementsCount = $('#rules-container > .col-md-6').length;
        var nextElementIndex = elementsCount + 1;
        this.setUrl('/discount/cart-rule/decision-rule/').setDataType('html').ajaxSubmit({
            elements: nextElementIndex
        }, 'displayNewDecisionRulesPoolOptions', {
            elementIndex: nextElementIndex
        });
    };

    SprykerAjaxCallbacks.prototype.displayNewDecisionRulesOptions = function(htmlResponse, options){
        var html = htmlResponse.replace(/decision_rule\[/g, 'cart_rule[decision_rules][rule_' + options.elementIndex + '][');
        $('#rules-container').append(html);
        $('#add-rules-container').removeClass('hidden');
        $('.load-cart-rules > .ajax-loader').addClass('hidden');
    };

    SprykerAjaxCallbacks.prototype.displayNewDecisionRulesPoolOptions = function(htmlResponse, options){
        var html = htmlResponse.replace(/decision_rule\[/g, 'voucher_codes[decision_rules][rule_' + options.elementIndex + '][');
        $('#rules-container').append(html);
        $('#add-rules-pool-container').removeClass('hidden');
        $('.load-cart-rules > .ajax-loader').addClass('hidden');
    };

    $('#add-rules-container').click(function(e){
        e.preventDefault();
        $(this).addClass('hidden');
        $('.load-cart-rules > .ajax-loader').removeClass('hidden');
        var sprykerAjax = new SprykerAjax();
        sprykerAjax.loadDecisionRulesOptions();
    });

    $('#add-rules-pool-container').click(function(e){
        e.preventDefault();
        $(this).addClass('hidden');
        $('.load-cart-rules > .ajax-loader').removeClass('hidden');
        var sprykerAjax = new SprykerAjax();
        sprykerAjax.loadDecisionRulesPoolOptions();
    });

});
