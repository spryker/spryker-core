/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

SprykerAjax.loadDecisionRulesOptions = function(element, mainFormName){
    var elementsCount = $('#rules-container > .col-md-6').length;
    var nextElementIndex = elementsCount + 1;
    var options = {
        elements: nextElementIndex
    };
    this.setUrl('/discount/cart-rule/decision-rule/')
        .setDataType('html')
        .ajaxSubmit(options, function(ajaxHtmlResponse, options){
            var html = ajaxHtmlResponse.replace(/decision_rule\[/g, mainFormName + '[decision_rules][rule_' + nextElementIndex + '][');
            $('#rules-container').append(html);
            element.children('i').addClass('hidden');
        }
    );
};

SprykerAjax.loadCollectorPlugins = function(element, mainFormName){
    var elementsCount = $('#collector-container > .col-md-6').length;
    var nextElementIndex = elementsCount + 1;
    var options = {
        elements: nextElementIndex
    };
    this.setUrl('/discount/cart-rule/collector-plugins/')
        .setDataType('html')
        .ajaxSubmit(options, function(ajaxHtmlResponse){
            var html = ajaxHtmlResponse.replace(/decision_rule\[/g, mainFormName + '[collector_plugins][plugin_' + nextElementIndex + '][');
            $('#collector-container').append(html);
            element.children('i').addClass('hidden');
        }
    );
};

module.exports = {
    loadCartRulesForm: function(element, mainFormName){
        element.children('i').removeClass('hidden');
        SprykerAjax.loadDecisionRulesOptions(element, mainFormName);
    },
    loadCollectorPluginForm: function(element, mainFormName){
        element.children('i').removeClass('hidden');
        SprykerAjax.loadCollectorPlugins(element, mainFormName);
    }
};
