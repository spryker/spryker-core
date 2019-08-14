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
    responseData,
    successCallback
) {
    this.data = {};
    this.trigger = $trigger;
    this.target = $target;
    this.requestUrl = requestUrl;
    this.dataKey = dataKey;
    this.responseData = responseData;
    this.successCallback = successCallback;

    this.mapEvents();
}

DependentSelectBox.prototype.mapEvents = function() {
    var self = this;

    this.trigger.on('change', function () {
        self.getData($(this));
        self.requestData();
    });
};

DependentSelectBox.prototype.getData = function(trigger) {
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
            self.updateTargetSelectBox(data);
            self.successCallback ? self.successCallback() : null;
        }
    });
};

DependentSelectBox.prototype.updateTargetSelectBox = function(data) {
    var self = this;

    if (data.length === 0) {
        this.clearTargetSelectBox(true);

        return;
    }

    this.clearTargetSelectBox(false);
    $.each(data[this.responseData.response], function(index, element) {
        self.target.append($('<option value="'+ element[self.responseData.value] +'">'+ element[self.responseData.text] +'</option>'));
    });
};

DependentSelectBox.prototype.clearTargetSelectBox = function(isDisabled) {
    this.target.attr('disabled', isDisabled);
    this.target.find('option:gt(0)').remove();
};

module.exports = DependentSelectBox;
