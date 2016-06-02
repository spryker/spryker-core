/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
//require('dot/doT');
//require('jquery-extendext');
require('sql-parser/browser/sql-parser');
require('jquery-query-builder');
require('../../sass/main.scss');

//var QueryBuilder = require('./legacy/query-builder.js');

function SprykerQueryBuilder(sqlQuery, ajaxUrl){
    var self = this;
    console.log('create object');
    this.getFiltersUrl = ajaxUrl;
    this.sql = sqlQuery;
    this.builder = '';
    this.init = function(){
        console.log('instantiate object');
        self.builder = $('#builder');
        self.createBuilder();
    };
}

SprykerQueryBuilder.prototype.createBuilder = function(){
    console.log('create builder');
    var self = this;
    $.get(self.getFiltersUrl).done(function(filters){
        console.log('ajax success');
        console.log(filters);


        console.log(self, self.builder);

        $(self.builder).queryBuilder({
            filters: filters
        });
        //self.builder.queryBuilder('setRulesFromSQL', self.sql);
    });
};

SprykerQueryBuilder.prototype.saveQuery = function(){
    console.log('save query');
    var result = this.builder.queryBuilder('getSQL', false);

    if (result.sql.length) {
        $('#discount_discountCalculator_collector_query_string').val(result.sql);
    }
};

var QueryBuilder = SprykerQueryBuilder;
var sqlBuilder;

function loadSqlQuery(){
    console.log('load sql query');
    var inputElement = $('#discount_discountCalculator_collector_query_string');
    var sqlRules = inputElement.val();
    var ajaxUrl = inputElement.data('url');

    sqlBuilder = new QueryBuilder(sqlRules, ajaxUrl);
    sqlBuilder.init();
}

$(document).ready(function(){
    $('#create-discount-button').on('click', function() {
        $('#discount-form').submit();
    });

    $('.tabs-manager .btn-tab-previous').on('click', function(){
        $(this).
            closest('.tabs-manager').
            children('.nav').
            children('.active').
            prev('li').
            find('a').
            trigger('click');
    });

    $('.tabs-manager .btn-tab-next').on('click', function(){
        $(this).
            closest('.tabs-manager').
            children('.nav').
            children('.active').
            next('li').
            find('a').
            trigger('click');
    });

    $('#discount_discountGeneral_valid_from').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function(selectedDate){
            $('#discount_discountGeneral_valid_to').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#discount_discountGeneral_valid_to').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function(selectedDate){
            $('#discount_discountGeneral_valid_from').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('#btn-get').on('click', function() {
        sqlBuilder.saveQuery();
    });

    //$('#reload').on('click', function(){
    //    loadSqlQuery();
    //});

    loadSqlQuery();
});
