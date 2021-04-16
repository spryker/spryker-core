/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

'use strict';

function FormAction(options) {
    $.extend(this, options);

    this.$table = $(this.tableSelector);
    this.$item = this.$table.find(this.itemSelector).not(':disabled');
    this.$actionButton = $(this.actionButtonSelector);
    this.checkedItemIds = [];

    this.init = function () {
        this.mapEvents();
    };

    this.mapEvents = function () {
        var self = this;

        this.$actionButton.on('click', function (event) {
            event.preventDefault();
            self.updateFormAction($(this));
        });
    };

    this.updateFormAction = function ($actionButton) {
        var $form = $actionButton.closest('form');
        var formUrl = decodeURI($form.attr('action'));

        this.setCheckedItemIds();

        if (this.checkedItemIds.length) {
            formUrl =
                formUrl.replace(/&items\[(\d+)?\]=\d+/g, '') +
                '&' +
                $.param({
                    items: this.checkedItemIds,
                });
        }

        this.formSubmit($actionButton, $form, formUrl);
    };

    this.setCheckedItemIds = function () {
        var self = this;

        this.$item.each(function () {
            if (!$(this).prop('checked')) {
                return;
            }

            self.checkedItemIds.push($(this).val());
        });
    };

    this.formSubmit = function ($button, $form, formUrl) {
        $button.prop('disabled', true).addClass('disabled');
        $form.attr('action', formUrl);
        $form.submit();
    };

    this.init();
}

module.exports = FormAction;
