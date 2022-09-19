'use strict';

var AutoNumeric = require('autonumeric');

function initFormattedNumber() {
    var numberInputs = Array.from(document.getElementsByClassName('js-formatted-number-input'));

    numberInputs.forEach(function (numberInput) {
        var hiddenInput = document.getElementsByClassName(numberInput.dataset.target)[0];
        var config = {
            digitGroupSeparator: numberInput.dataset.groupSeparator,
            decimalCharacter: numberInput.dataset.decimalSeparator,
            decimalPlaces: numberInput.dataset.decimalRounding,
            allowDecimalPadding: false,
            digitalGroupSpacing: 3,
            modifyValueOnWheel: false,
        };

        numberInput.value = hiddenInput.value;
        var formattedInput = new AutoNumeric(numberInput, config);

        numberInput.addEventListener('input', function () {
            hiddenInput.value = formattedInput.rawValue;
        });
    });
}

module.exports = initFormattedNumber;
