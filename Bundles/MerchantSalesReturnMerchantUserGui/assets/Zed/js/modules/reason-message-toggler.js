/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

'use strict';

function ReasonMessageToggler(options) {
    $.extend(this, options);

    this.$select = $(this.selectSelector);

    this.init = function () {
        this.mapEvents();
    };

    this.mapEvents = function () {
        var self = this;

        this.$select.on('change', function () {
            self.toggleMessageBlock($(this));
        });
    };

    this.toggleMessageBlock = function ($select) {
        var targetClassName = $select.data('target');
        var $target = $('.' + targetClassName);
        var isToggleValueSelected = this.toggleValue === $select.val();

        if (isToggleValueSelected) {
            $target.removeClass('hidden');

            return;
        }

        $target.addClass('hidden');
    };

    this.init();
}

module.exports = ReasonMessageToggler;
