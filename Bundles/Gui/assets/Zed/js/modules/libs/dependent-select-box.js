/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

function DependentSelectBox(options) {
    var _self = this;
    this.data = {};
    this.requestMethod = 'POST';

    $.extend(this, options);

    this.init = function () {
        this.mapEvents();
    };

    this.mapEvents = function () {
        this.$trigger.on('change', function () {
            _self.getData($(this));
            _self.requestData();
        });
    };

    this.getData = function (trigger) {
        if (this.dataKey.length) {
            this.data[this.dataKey] = trigger.val();

            return;
        }

        this.data = trigger.val();
    };

    this.requestData = function () {
        this.$target.attr('disabled', true);

        $.ajax({
            url: this.requestUrl,
            type: this.requestMethod,
            data: this.data,
            success: function (data) {
                _self.updateTargetSelectBox(data);
                _self.successCallback ? _self.successCallback(data) : null;
            },
        });
    };

    this.updateTargetSelectBox = function (data) {
        if (data.length === 0) {
            this.clearTargetSelectBox(true);

            return;
        }

        this.clearTargetSelectBox(false);
        this.fillTargetSelectBox(data);
    };

    this.clearTargetSelectBox = function (isDisabled) {
        this.$target.attr('disabled', isDisabled);
        this.$target.find('option:gt(0)').remove();
    };

    this.fillTargetSelectBox = function (data) {
        $.each(data[this.responseData.response], function (index, element) {
            _self.$target.append(
                $(
                    '<option value="' +
                        element[_self.responseData.value] +
                        '">' +
                        element[_self.responseData.text] +
                        '</option>',
                ),
            );
        });
    };

    this.init();
}

module.exports = DependentSelectBox;
