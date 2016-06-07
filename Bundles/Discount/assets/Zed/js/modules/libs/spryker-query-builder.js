'use strict';

window.SQLParser = require('sql-parser/browser/sql-parser');
require('jquery-query-builder');

function SprykerQueryBuilder(sqlQuery, ajaxUrl, inputElement, targetElement){
    var self = this;
    this.builder = null;
    this.getFiltersUrl = ajaxUrl;
    this.sql = sqlQuery;
    this.displayQueryBuilder = true;
    this.inputElement = inputElement;
    this.targetElement = targetElement;
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
        self.builder.queryBuilder('setRulesFromSQL', self.sql);
    });
};

SprykerQueryBuilder.prototype.toggleButton = function(){
    var self = this;
    var inputElementContainer = $(self.inputElement).parent();

    if (self.displayQueryBuilder === true) {
        self.saveQuery();
        inputElementContainer.removeClass('hidden');
        self.builder.queryBuilder('destroy');
        self.displayQueryBuilder = false;
    } else {
        inputElementContainer.addClass('hidden');
        self.displayQueryBuilder = true;
        self.sql = $(self.inputElement).val();
        self.createBuilder();
    }
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
