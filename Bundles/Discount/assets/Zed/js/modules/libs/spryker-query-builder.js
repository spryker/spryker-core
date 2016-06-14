'use strict';

window.SQLParser = require('sql-parser/browser/sql-parser');
require('jquery-query-builder');

function SprykerQueryBuilder(options) {
    var self = this;
    this.builder = null;
    this.displayQueryBuilder = true;
    this.getFiltersUrl = options.ajaxUrl;
    this.sql = options.sqlQuery;
    this.inputElement = options.inputElement;
    this.targetElement = options.targetElement;
    this.label = options.label || 'Build Query';
    this.init = function(){
        self.builder = $(self.targetElement);
        self.createBuilder();
    };

    this.init();
}

SprykerQueryBuilder.prototype.createBuilder = function(){

    var self = this;
    $.get(self.getFiltersUrl).done(function(filters){
        self.builder.queryBuilder({
            filters: filters,
            sqlOperators: self.getSqlOperators(),
            sqlRuleOperator: self.getSqlRuleOperators()
        });
        self.builder.prepend('<label class="control-label query-builder-label">' + self.label + '</label>');
        if (typeof self.sql !== 'undefined' && self.sql !== '') {
            self.builder.queryBuilder('setRulesFromSQL', self.sql);
        }
    });
};

SprykerQueryBuilder.prototype.toggleButton = function(event){
    var self = this;
    var inputElementContainer = $(self.inputElement).parent();
    var label = '';
    var button = $(event.target);

    if (self.displayQueryBuilder === true) {
        self.saveQuery();
        inputElementContainer.removeClass('hidden');
        self.builder.addClass('hidden');
        self.builder.queryBuilder('destroy');
        self.displayQueryBuilder = false;
        self.builder.children('.query-builder-label').remove();
        label = button.data('label-query-builder');
    } else {
        inputElementContainer.addClass('hidden');
        self.builder.removeClass('hidden');
        self.displayQueryBuilder = true;
        self.sql = $(self.inputElement).val();
        self.createBuilder();
        label = button.data('label-plain-query');

    }
    button.text(label);
};

SprykerQueryBuilder.prototype.getSqlOperators = function(){
    return {
        contains: {
            op : 'CONTAINS ?',
            mod: '{0}'
        },
        not_contains: {
            op : 'DOES NOT CONTAIN ?',
            mod: '{0}'
        },
        in: {
            op : 'IS IN ?',
            sep: ', '
        },
        not_in: {
            op : 'IS NOT IN ?',
            sep: ', '
        }
    };
};

SprykerQueryBuilder.prototype.getSqlRuleOperators = function(){
    return {
        'CONTAINS': function(v) {
            return {
                val: v,
                op: 'contains'
            };
        },
        'DOES NOT CONTAIN': function(v) {
            return {
                val: v,
                op: 'not_contains'
            };
        },
        'IS IN': function(v) {
            return {
                val: v,
                op: 'in'
            };
        },
        'IS NOT IN': function(v) {
            return {
                val: v,
                op: 'not_in'
            };
        }
    };
};


SprykerQueryBuilder.prototype.saveQuery = function(){

    var result = this.builder.queryBuilder('getSQL', false);

    if (result.sql.length) {
        this.inputElement.val(result.sql);
    }
};

module.exports = SprykerQueryBuilder;
