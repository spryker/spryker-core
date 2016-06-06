/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
window.SQLParser = require('sql-parser/browser/sql-parser');
require('jquery-query-builder');
require('../../sass/main.scss');

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
        this.inputElement.val(result.sql);
    }
};

var sqlCalculationBuilder;
var sqlConditionBuilder;

function loadSqlCalculationQuery(){

    var inputElement = $('#discount_discountCalculator_collector_query_string');
    var sqlRules = inputElement.val();
    var ajaxUrl = inputElement.data('url');

    sqlCalculationBuilder = new SprykerQueryBuilder(sqlRules, ajaxUrl, inputElement, '#builder_calculation');
}

function loadSqlConditionsQuery(){

    var inputElement = $('#discount_discountCondition_decision_rule_query_string');
    var sqlRules = inputElement.val();
    var ajaxUrl = inputElement.data('url');

    sqlConditionBuilder = new SprykerQueryBuilder(sqlRules, ajaxUrl, inputElement, '#builder_condition');
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

    $('#btn-calculation-get').on('click', function() {
        sqlCalculationBuilder.saveQuery();
    });
    $('#btn-condition-get').on('click', function() {
        sqlConditionBuilder.saveQuery();
    });

    loadSqlCalculationQuery();
    loadSqlConditionsQuery();
});
