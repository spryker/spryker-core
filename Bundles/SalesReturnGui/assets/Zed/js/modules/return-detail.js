/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    var $table = $('.js-return-items-table');
    var $checkAllItems = $table.find('.js-check-all-items');
    var $checkItem = $table.find('.js-check-item').not(':disabled');

    setFormAction($checkItem);
    setItemCounter($checkItem, $table);
    setItemCounter($checkAllItems, $table);
    toggleReturnItems($checkAllItems, $checkItem, $table);
    toggleReturnReasonMessage();
});

function setFormAction($checkItem) {
    var $actionButton = $('.js-return-bulk-trigger-buttons button');
    var checkedItemIds = [];

    $actionButton.on('click', function(event) {
        event.preventDefault();

        var $form = $(this).closest('form');
        var formUrl = decodeURI($form.attr('action'));

        $checkItem.each(function () {
            if (!$(this).prop('checked')) {
                return;
            }

            checkedItemIds.push($(this).val());
            console.log(checkedItemIds);
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
}

function setItemCounter($item, $table) {
    $item.on('change', function() {
        var checkedItems = $table.find('.js-check-item:checked').length;

        updateItemCounter(checkedItems);
    });
}

function updateItemCounter(count) {
    var $wrapper = $('.js-item-counter-wrapper');
    var $counter = $('.js-item-counter');

    if (count) {
        $counter.text(count);
        $wrapper.removeClass('hidden');

        return;
    }

    $wrapper.addClass('hidden');
}

function toggleReturnItems($checkAllItems, $checkItem, $table) {
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

function toggleReturnReasonMessage() {
    var $select = $('.js-select-reason');
    var toggleValue = 'custom_reason';

    $select.each(function (index, item) {
        $(item).on('change', function () {
            var targetClassName = $(this).attr('data-target');
            var $target = $('.' + targetClassName);
            var isToggleValueSelected = toggleValue === $(this).val();
            console.log($target, '$target');

            if (isToggleValueSelected) {
                $target.removeClass('hidden');

                return;
            }

            $target.addClass('hidden');
        });
    });
}
