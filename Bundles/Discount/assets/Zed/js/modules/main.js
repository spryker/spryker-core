/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var SqlFactory = require('./libs/sql-factory');

require('jquery-datetimepicker');
require('../../sass/main.scss');

function setDiscountAmountSymbol() {
    var value = $(this).val();
    var $amountAddon = $('#discount_discountCalculator_amount + .input-group-addon');

    if (/percent/i.test(value)) {
        $amountAddon.html('&#37;');
    } else {
        $amountAddon.html('&euro;');
    }
}

$(document).ready(function () {
    var sqlCalculationBuilder = SqlFactory(
        '#discount_discountCalculator_collector_query_string',
        '#builder_calculation',
    );
    var sqlConditionBuilder = SqlFactory(
        '#discount_discountCondition_decision_rule_query_string',
        '#builder_condition',
        true,
    );
    var isQueryStringCollectorSelected = $('#discount_discountCalculator_collectorStrategyType_0').is(':checked');

    $('#create-discount-button').on('click', function (e) {
        e.preventDefault();

        $(this).prop('disabled', true).addClass('disabled');

        if (isQueryStringCollectorSelected) {
            sqlCalculationBuilder.saveQuery();
        }

        sqlConditionBuilder.saveQuery();

        $('#discount-form').submit();
    });

    $('#btn-calculation-get').on('click', function (event) {
        sqlCalculationBuilder.toggleButton(event);
    });

    $('#btn-condition-get').on('click', function (event) {
        sqlConditionBuilder.toggleButton(event);
    });

    setDiscountAmountSymbol.apply($('#discount_discountCalculator_calculator_plugin'));
    $('#discount_discountCalculator_calculator_plugin').on('change', setDiscountAmountSymbol);

    var $inputFrom = $('#discount_discountGeneral_valid_from');
    var $inputTo = $('#discount_discountGeneral_valid_to');
    var defaultDateFormat = 'd.m.Y H:i';
    var inputFromFormat = $inputFrom.data('format') ?? defaultDateFormat;
    var inputToFormat = $inputTo.data('format') ?? defaultDateFormat;

    $inputFrom.datetimepicker({
        format: inputFromFormat,
        defaultTime: '00:00',
        todayButton: false,
        onShow: function () {
            if (!$inputTo.val()) {
                return;
            }

            this.setOptions({
                maxDate: $inputTo.datetimepicker('getValue'),
            });
        },
        onClose: function () {
            if (!$inputTo.val()) {
                return;
            }

            var startDate = $inputFrom.datetimepicker('getValue');
            var endDate = $inputTo.datetimepicker('getValue');
            if (startDate > endDate) {
                $inputTo.datetimepicker({ value: startDate });
            }
        },
    });

    $inputTo.datetimepicker({
        format: inputFromFormat,
        defaultTime: '00:00',
        todayButton: false,
        onShow: function () {
            if (!$inputFrom.val()) {
                return;
            }

            this.setOptions({
                minDate: $inputFrom.datetimepicker('getValue'),
            });
        },
        onClose: function () {
            if (!$inputFrom.val()) {
                return;
            }

            var startDate = $inputFrom.datetimepicker('getValue');
            var endDate = $inputTo.datetimepicker('getValue');
            if (startDate > endDate) {
                $inputFrom.datetimepicker({ value: endDate });
            }
        },
    });

    $('#discount_discountCalculator_collectorStrategyType input').each(function (index, element) {
        $('#collector-type-' + $(element).val()).hide();
        if ($(element).is(':checked')) {
            $('#collector-type-' + $(element).val()).show();
        }
    });

    $('#discount_discountCalculator_collectorStrategyType input').on('click', function (event) {
        $('#discount_discountCalculator_collectorStrategyType input').each(function (index, element) {
            $('#collector-type-' + $(element).val()).hide();
        });

        $('#collector-type-' + $(event.target).val()).show();
    });

    $('#discount_discountCalculator_calculator_plugin').on('change', function (event) {
        $('.discount-calculation-input-type').each(function (index, element) {
            $(element).hide();
        });

        var activeCalculatorInputType = $('#discount_discountCalculator_calculator_plugin :selected').data(
            'calculator-input-type',
        );
        $('#' + activeCalculatorInputType).show();
    });

    var activeCalculatorInputType = $('#discount_discountCalculator_calculator_plugin :selected').data(
        'calculator-input-type',
    );
    $('#' + activeCalculatorInputType).show();
});
