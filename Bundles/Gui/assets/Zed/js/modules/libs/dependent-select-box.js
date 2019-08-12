/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function DependentSelectBox(
    $trigger,
    $target,
    requestUrl,
    dataKey,
    successCallback
) {
    this.data = {};
    this.trigger = $trigger;
    this.target = $target;
    this.requestUrl = requestUrl;
    this.dataKey = dataKey;
    this.successCallback = successCallback;

    this.mapEvents();
}

DependentSelectBox.prototype.mapEvents = function() {
    var self = this;

    this.trigger.on('change', function () {
        self.prepareData($(this));
        self.requestData();
    });
};

DependentSelectBox.prototype.prepareData = function(trigger) {
    if (this.dataKey.length) {
        this.data[this.dataKey] = trigger.val();

        return;
    }

    this.data = trigger.val();
};

DependentSelectBox.prototype.requestData = function() {
    var self = this;

    $.ajax({
        url: this.requestUrl,
        type: 'POST',
        data: this.data,
        success: function(data) {
            self.setResponseData(data);
            self.successCallback ? self.successCallback() : null;
        }
    });
};

DependentSelectBox.prototype.setResponseData = function(data) {
    var self = this;

    if (!data) {
        this.target.attr('disabled', true);

        return;
    }

    this.target.attr('disabled', false);
    this.target.find('option:gt(0)').remove();
    $.each(data.currencies, function(key, currency) {
        self.target.append($('<option value="'+ currency.id_currency +'">'+ currency.code +'</option>'));
    });
};

module.exports = DependentSelectBox;
