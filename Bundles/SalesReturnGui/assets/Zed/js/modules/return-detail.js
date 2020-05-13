/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $('#return-bulk-trigger-buttons button').click(function (e) {
        e.preventDefault();
        var $table = $('#return-items-table');
        var $orderItems = $table.find('input[name="order-item"]');
        var checkedItemIds = [];
        var $form = $(this).closest('form');
        var formUrl = decodeURI($form.attr('action'));

        $orderItems.each(function () {
            if ($(this).prop('checked')) {
                checkedItemIds.push($(this).val());
            }
        });

        if (checkedItemIds.length) {
            formUrl = formUrl.replace(/&items\[(\d+)?\]=\d+/g, '') + '&' + $.param({
                items: checkedItemIds
            });
        }

        $(this).prop('disabled', true).addClass('disabled');
        $form.attr('action', formUrl);

        $form.submit();
    });

    $('.item-check, .check-all-orders').click(function () {
        var $table = $(this).closest('table');
        var count = $table.find('.item-check:checked').length;

        updateItemCounter(count);
    });

    toggleReturnItems();
});

function updateItemCounter(count) {
    var $wrapper = $('#item-counter-wrapper');

    if (count) {
        $('#item-counter').text(count);
        $wrapper.removeClass('hidden');

        return;
    }

    $wrapper.addClass('hidden');
}

function toggleReturnItems() {
    var $table = $('.js-create-return-table');
    var $checkAllItems = $table.find('.js-check-all-items');
    var $checkItem = $table.find('.js-check-item').not(':disabled');
    var totalItems = $table.find($checkItem).length;


    $checkItem.click(function() {
        var checkedItems = $table.find('.js-check-item:checked').length;

        if (totalItems === checkedItems) {
            $checkAllItems.prop('checked', true);
            console.log($checkAllItems);

            return;
        }

        $checkAllItems.prop('checked', false);
    });

    $checkAllItems.click(function() {
        $checkItem.prop('checked', $(this).prop('checked'));
    });
}
