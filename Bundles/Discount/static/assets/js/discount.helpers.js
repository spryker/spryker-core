function loadCartRulesForm(element, mainFormName) {
    element.children('i').removeClass('hidden');
    var sprykerAjax = new SprykerAjax();
    sprykerAjax.loadDecisionRulesOptions(element, mainFormName);
}

function loadCollectorPluginForm(element, mainFormName) {
    element.children('i').removeClass('hidden');
    var sprykerAjax = new SprykerAjax();
    sprykerAjax.loadCollectorPlugins(element, mainFormName);
}

SprykerAjax.prototype.loadDecisionRulesOptions = function(element, mainFormName){
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

SprykerAjax.prototype.loadCollectorPlugins = function(element, mainFormName){
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
