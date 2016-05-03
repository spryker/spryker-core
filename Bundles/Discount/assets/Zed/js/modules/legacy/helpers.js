/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

SprykerAjax.loadDecisionRulesOptions = function(element) {
    var elementsCount = $('#rules-container > .col-md-6').length;
    var collectionHolder = $('#rules-container');
    var prototype = collectionHolder.data('prototype');
    var newForm = prototype.replace(/__name__/g, elementsCount);
    collectionHolder.append(newForm);
    element.children('i').addClass('hidden');
};

SprykerAjax.loadCollectorPlugins = function(element) {
    var elementsCount = $('#collector-container > .col-md-6').length;
    var collectionHolder = $('#collector-container');
    var prototype = collectionHolder.data('prototype');
    var newForm = prototype.replace(/__name__/g, elementsCount);
    collectionHolder.append(newForm);
    element.children('i').addClass('hidden');
};

module.exports = {
    loadDecisionRuleForm: function(element, mainFormName){
        element.children('i').removeClass('hidden');
        SprykerAjax.loadDecisionRulesOptions(element, mainFormName);
    },
    loadCollectorPluginForm: function(element, mainFormName){
        element.children('i').removeClass('hidden');
        SprykerAjax.loadCollectorPlugins(element, mainFormName);
    }
};
