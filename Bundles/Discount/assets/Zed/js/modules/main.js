/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
var SqlFactory = require('./libs/sql-factory');

require('../../sass/main.scss');

$(document).ready(function(){

    var sqlCalculationBuilder = SqlFactory('#discount_discountCalculator_collector_query_string', '#builder_calculation');
    var sqlConditionBuilder = SqlFactory('#discount_discountCondition_decision_rule_query_string', '#builder_condition');

    $('#create-discount-button').on('click', function(element) {
        element.preventDefault();
        sqlCalculationBuilder.saveQuery();
        sqlConditionBuilder.saveQuery();

        $('#discount-form').submit();
    });

    $('#btn-calculation-get').click(function(event){
        sqlCalculationBuilder.toggleButton(event);
    });

    $('#btn-condition-get').click(function(event){
        sqlConditionBuilder.toggleButton(event);
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
});
