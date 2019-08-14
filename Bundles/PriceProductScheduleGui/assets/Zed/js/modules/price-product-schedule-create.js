/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var DependentSelectBox = require('ZedGuiModules/libs/dependent-select-box');

function PriceProductScheduleCreate(options) {
    $.extend(this, options);

    var self = this;

    this.init = function() {
        this.initActiveFromDatepicker();
        this.initActiveToDatepicker();
        this.hideTimezoneMessage();
        this.initDependentSelectBox();
    };

    this.initActiveFromDatepicker = function() {
        this.$activeFrom.datepicker({
            altFormat: "yy-mm-dd",
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            defaultData: 0,
        });
    };

    this.initActiveToDatepicker = function() {
        this.$activeTo.datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            defaultData: 0,
        });
    };

    this.toggleVisibility = function(display) {
        this.$activeFromTimezoneText.toggle(display);
        this.$activeToTimezoneText.toggle(display);
    };

    this.hideTimezoneMessage = function() {
        if (!this.$store.val()) {
            this.toggleVisibility(false);
        }
    };

    this.fillTimezoneMessage = function(data) {
        this.$timezone.each(function(index, element) {
            $(element).text(data.store.timezone);
        });
    };

    this.successCallback = function(data) {
        if (!data.store) {
            self.toggleVisibility(false);

            return;
        }

        self.fillTimezoneMessage(data);
        self.toggleVisibility(true);
    };

    this.initDependentSelectBox = function() {
        new DependentSelectBox({
            $trigger: this.$store,
            $target: this.$currency,
            requestUrl: this.requestUrl,
            dataKey: this.dataKey,
            responseData: this.currencies,
            successCallback: this.successCallback
        });
    };

    this.init();
}

module.exports = PriceProductScheduleCreate;
