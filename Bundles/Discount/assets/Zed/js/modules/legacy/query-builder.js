'use strict';

//require('ZedGui');
require('sql-parser/browser/sql-parser');
//require('jQuery-QueryBuilder/dist/js/query-builder');

function SprykerQueryBuilder(sqlQuery, ajaxUrl){
    this.getFiltersUrl = ajaxUrl;
    this.sql = sqlQuery;
    this.builder = '';
    this.init = function(){
        this.builder = $('#discount_discountCalculator_collector_query_string');
        this.createBuilder();
    };
}

SprykerQueryBuilder.prototype.createBuilder = function(){
    var self = this;
    $.get(self.getFiltersUrl).done(function(filters){
        self.builder.queryBuilder({
            filters: filters
        });
        self.builder.queryBuilder('setRulesFromSQL', self.sql);
    });
};

SprykerQueryBuilder.prototype.saveQuery = function(){
    var result = this.builder.queryBuilder('getSQL', false);

    if (result.sql.length) {
        $('#sqlQuery').val(result.sql);
    }
};

module.exports = SprykerQueryBuilder;
