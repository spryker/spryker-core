export class DatePicker {
    init() {
        this.initSingle();
        this.initRange();
    }

    initSingle() {
        const $date = $('.js-date');

        if (!$date) {
            return;
        }

        $date.datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 3,
            defaultData: 0,
            onClose: function (selectedDate) {
                $date.datepicker('option', 'minDate', selectedDate);
            },
        });
    }

    initRange() {
        const $fromDate = $('.js-from-date');
        const $toDate = $('.js-to-date');

        if (!$fromDate || !$toDate) {
            return;
        }

        $fromDate.datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 3,
            maxDate: $toDate.val(),
            defaultData: 0,
            onClose: function (selectedDate) {
                $toDate.datepicker('option', 'minDate', selectedDate);
            },
        });

        $toDate.datepicker({
            defaultData: 0,
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 3,
            minDate: $fromDate.val(),
            onClose: function (selectedDate) {
                $fromDate.datepicker('option', 'maxDate', selectedDate);
            },
        });
    }
}
