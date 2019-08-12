/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function DependentSelectBox(
    // successCallback,
    $trigger,
    $target,
    requestUrl,
    property
) {
    this.trigger = $trigger;
    this.target = $target;
    this.data = {};
    this.requestUrl = requestUrl;
    this.property = property;
    // this.successCallback = successCallback;
}

DependentSelectBox.prototype.mapEvents = function() {
    var self = this;

    this.trigger.on('change', function () {
        self.prepareData($(this));
        self.requestData();
    });
};

DependentSelectBox.prototype.prepareData = function(trigger) {
    if (this.property.length) {
        this.data[this.property] = trigger.val();

        return;
    }

    this.data = trigger.val();
};

DependentSelectBox.prototype.requestData = function() {
    var self = this;
    // var data = {};
    // data.idStore = this.trigger.val();

    $.ajax({
        url: this.requestUrl,
        type: 'POST',
        data: this.data,
        success: function(data) {
            self.target.find('option:gt(0)').remove();
            $.each(data.currencies, function(key, currency) {
                self.target.append($('<option value="'+ currency.id_currency +'">'+ currency.code +'</option>'));
            });
            // self.callback();
        }
        // success: this.successCallback
    });
};

module.exports = DependentSelectBox;



// $(document).ready(function() {
//     var $activeFromDate = $('#price_product_schedule_activeFrom_date');
//     var $activeToDate = $('#price_product_schedule_activeTo_date');
//     var $store = $('#price_product_schedule_priceProduct_moneyValue_store_idStore');
//     var $currency = $('#price_product_schedule_priceProduct_moneyValue_currency_idCurrency');
//     $activeFromDate.datepicker({
//         altFormat: "yy-mm-dd",
//         dateFormat: 'yy-mm-dd',
//         changeMonth: true,
//         defaultData: 0,
//     });
//     $activeToDate.datepicker({
//         dateFormat: 'yy-mm-dd',
//         changeMonth: true,
//         defaultData: 0,
//     });
//
//     $store.change(function() {
//         var data = {};
//         data.idStore = $store.val();
//         $currency.find('option:gt(0)').remove();
//         $.ajax({
//             url: '/currency/currencies-for-store',
//             type: 'POST',
//             data: data,
//             success: function(data) {
//                 $.each(data.currencies, function(key, currency) {
//                     $currency.append($('<option value="'+ currency.id_currency +'">'+ currency.code +'</option>'));
//                 });
//                 $('#active_from_timezone').html(data.timezoneText);
//                 $('#active_to_timezone').html(data.timezoneText);
//             }
//         });
//     });
// });
