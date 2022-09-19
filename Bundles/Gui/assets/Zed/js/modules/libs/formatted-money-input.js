'use strict';

var AutoNumeric = require('autonumeric');

function initFormattedMoney() {
    var moneyInputs = Array.from(document.getElementsByClassName('js-formatted-money-input'));

    moneyInputs.forEach(function (moneyInput) {
        var hiddenInput = document.getElementsByClassName(moneyInput.dataset.target)[0];
        var config = {
            digitGroupSeparator: moneyInput.dataset.groupSeparator,
            decimalCharacter: moneyInput.dataset.decimalSeparator,
            decimalPlaces: moneyInput.dataset.decimalRounding,
            allowDecimalPadding: moneyInput.hasAttribute('data-decimal-filling'),
            digitalGroupSpacing: 3,
            modifyValueOnWheel: false,
        };

        moneyInput.value = hiddenInput.value;
        var formattedInput = new AutoNumeric(moneyInput, config);

        moneyInput.addEventListener('input', function () {
            hiddenInput.value = formattedInput.rawValue;
        });
    });
}

module.exports = initFormattedMoney;
