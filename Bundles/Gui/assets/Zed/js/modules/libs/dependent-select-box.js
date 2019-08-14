/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function DependentSelectBox(options) {
    $.extend(this, options);

    this.data = {};

    this.init = function() {
        this.mapEvents();
    };

    this.mapEvents = function() {
        var self = this;

        this.$trigger.on('change', function () {
            self.getData($(this));
            self.requestData();
        });
    };

    this.getData = function(trigger) {
        if (this.dataKey.length) {
            this.data[this.dataKey] = trigger.val();

            return;
        }

        this.data = trigger.val();
    };

    this.requestData = function() {
        var self = this;

        $.ajax({
            url: this.requestUrl,
            type: 'POST',
            data: this.data,
            success: function(data) {
                self.updateTargetSelectBox(data);
                self.successCallback ? self.successCallback(data) : null;
            }
        });
    };

    this.updateTargetSelectBox = function(data) {
        if (data.length === 0) {
            this.clearTargetSelectBox(true);

            return;
        }

        this.clearTargetSelectBox(false);
        this.fillTargetSelectBox(data);
    };

    this.clearTargetSelectBox = function(isDisabled) {
        this.$target.attr('disabled', isDisabled);
        this.$target.find('option:gt(0)').remove();
    };

    this.fillTargetSelectBox = function(data) {
        var self = this;

        $.each(data[this.responseData.response], function(index, element) {
            self.$target.append($('<option value="'+ element[self.responseData.value] +'">'+ element[self.responseData.text] +'</option>'));
        });
    };

    this.init();
}

module.exports = DependentSelectBox;
