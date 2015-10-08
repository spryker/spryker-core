'use strict';

$(function(){

    SprykerAjax.prototype.loadDecisionRulesOptions = function(element){
        var elementsCount = $('#rules-container > .col-md-6').length;
        var nextElementIndex = elementsCount + 1;
        this.setUrl('/discount/cart-rule/decision-rule/').setDataType('html').ajaxSubmit({
            elements: nextElementIndex
        }, function(ajaxHtmlResponse, options){
            var html = ajaxHtmlResponse.replace(/decision_rule\[/g, 'cart_rule[decision_rules][rule_' + options.elementIndex + '][');
            $('#rules-container').append(html);
            $('#add-rules-container').removeClass('hidden');
            element.children('i').addClass('hidden');
        }, {
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

    SprykerAjax.prototype.loadCollectorPlugins = function(element){
        var elementsCount = $('#collector-container > .col-md-6').length;
        var nextElementIndex = elementsCount + 1;
        var options = {
            elements: nextElementIndex
        };
        this.setUrl('/discount/cart-rule/collector-plugins/')
            .setDataType('html')
            .ajaxSubmit(options, function(ajaxHtmlResponse){
                var html = ajaxHtmlResponse.replace(/decision_rule\[/g, 'cart_rule[collector_plugins][plugin_' + nextElementIndex + '][');
                $('#collector-container').append(html);
                element.children('i').addClass('hidden');
            }
        );
    };

    SprykerAjaxCallbacks.prototype.displayNewDecisionRulesPoolOptions = function(htmlResponse, options){
        var html = htmlResponse.replace(/decision_rule\[/g, 'voucher_codes[decision_rules][rule_' + options.elementIndex + '][');
        $('#rules-container').append(html);
        $('#add-rules-pool-container').removeClass('hidden');
        $('.load-cart-rules > .ajax-loader').addClass('hidden');
    };

    $('#add-rules-container').click(function(e){
        e.preventDefault();
        $(this).children('i').removeClass('hidden');
        $('.load-cart-rules > .ajax-loader').removeClass('hidden');
        var sprykerAjax = new SprykerAjax();
        sprykerAjax.loadDecisionRulesOptions($(this));
    });

    $('#add-collector-container').click(function(e){
        e.preventDefault();
        $(this).children('i').removeClass('hidden');
        //$('.load-cart-rules > .ajax-loader').removeClass('hidden');
        var sprykerAjax = new SprykerAjax();
        sprykerAjax.loadCollectorPlugins($(this));
    });

    $('#add-rules-pool-container').click(function(e){
        e.preventDefault();
        $(this).addClass('hidden');
        $('.load-cart-rules > .ajax-loader').removeClass('hidden');
        var sprykerAjax = new SprykerAjax();
        sprykerAjax.loadDecisionRulesPoolOptions();
    });

});
