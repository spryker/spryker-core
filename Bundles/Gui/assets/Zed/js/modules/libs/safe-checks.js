'use strict';

var safeSubmitSelector = '.safe-submit';
var safeDatetimeSelector = '.safe-datetime[type=date], .safe-datetime[type=datetime], .safe-datetime[type=datetime-local]';

/* Prevent .save-submit items to be pressed twice */
function addSafeSubmitCheck() { 
    $('body').on('click', safeSubmitSelector, function () {
        var $item = $(this);
        var $forms = $item.parents('form');
        var isValid = true;

        function disableTrigger() {
            $item
                .prop('disabled', true)
                .addClass('disabled')
                .off('click');
        }

        if ($forms.length > 0) {
            isValid = !!$forms[0].checkValidity ? $forms[0].checkValidity() : isValid;
        }

        if (isValid) {
            setTimeout(disableTrigger);
        }

        return true;
    });
}

/* Prevent .save-datetime inputs to show native datepickers */
function addSafeDatetimeCheck() { 
    $('body').on('click', safeDatetimeSelector, function (e) {
        function disableNativeWindow() {
            e.preventDefault();
        }

        setTimeout(disableNativeWindow);

        return false;
    });
}

module.exports = {
    addSafeSubmitCheck: addSafeSubmitCheck,
    addSafeDatetimeCheck: addSafeDatetimeCheck
}
