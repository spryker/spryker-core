'use strict';

window.SQLParser = require('sql-parser/browser/sql-parser');
require('jquery-query-builder');

function SprykerQueryBuilder(sqlQuery, ajaxUrl, inputElement, targetElement){
    var self = this;
    this.getFiltersUrl = ajaxUrl;
    this.sql = sqlQuery;
    this.builder = null;
    this.inputElement = inputElement;
    this.init = function(){
        self.builder = $(targetElement);
        self.createBuilder();
    };

    this.init();
}

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

SprykerQueryBuilder.prototype.saveQuery = function(){

    var result = this.builder.queryBuilder('getSQL', false);

    if (result.sql.length) {
        this.inputElement.val(result.sql);
    }
};

module.exports = SprykerQueryBuilder;
