'use strict';

$(function(){

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

        SprykerAjaxCallbacks.prototype.displayNewDecisionRulesOptions = function(htmlResponse, options){
            var html = htmlResponse.replace(/decision_rule\[/g, 'cart_rule[decision_rules][rule_' + options.elementIndex + '][');
            $('#rules-container').append(html);
            $('#add-rules-container').removeClass('hidden');
            $('.load-cart-rules > .ajax-loader').addClass('hidden');
        };

        $('#add-rules-container').click(function(e){
            e.preventDefault();
            $('#add-rules-container').addClass('hidden');
            $('.load-cart-rules > .ajax-loader').removeClass('hidden');
            var sprykerAjax = new SprykerAjax();
            sprykerAjax.loadDecisionRulesOptions();
        });

    });

});
