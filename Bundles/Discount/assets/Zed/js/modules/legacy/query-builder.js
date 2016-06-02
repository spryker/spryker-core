'use strict';

//require('ZedGui');
//require('sql-parser/browser/sql-parser');
//require('jQuery-QueryBuilder/dist/js/query-builder');

function SprykerQueryBuilder(sqlQuery, ajaxUrl){
    console.log('create object');
    this.getFiltersUrl = ajaxUrl;
    this.sql = sqlQuery;
    this.builder = '';
    this.init = function(){
        console.log('instantiate object');
        this.builder = $('#discount_discountCalculator_collector_query_string');
        this.createBuilder();
    };
}

SprykerQueryBuilder.prototype.createBuilder = function(){
    console.log('create builder');
    var self = this;
    $.get(self.getFiltersUrl).done(function(filters){
        console.log('ajax success');
        console.log(filters);
        self.builder.queryBuilder({
            filters: filters
        });
        //self.builder.queryBuilder('setRulesFromSQL', self.sql);
    });
};

SprykerQueryBuilder.prototype.saveQuery = function(){
    console.log('save query');
    var result = this.builder.queryBuilder('getSQL', false);

    if (result.sql.length) {
        $('#sqlQuery').val(result.sql);
    }
};

module.exports = SprykerQueryBuilder;
