/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $('.sales-order-item-group-element button').click(function(e) {
        e.preventDefault();
        var keyItemGroup = $(this).closest('.sales-order-item-group-element').data('group-key');
        var $groupTable = $('.order-group-items-table-' + keyItemGroup);
        var $idGroupItems = $groupTable.find('input[name="order-item"]');
        var idGroupItemsCheckedList = [];
        var idGroupItemsFullList = [];
        var $form = $(this).closest('form');
        var formAction = $form.attr('action');

        $idGroupItems.each(function () {
            idGroupItemsFullList.push($(this).val());

            if ($(this).prop('checked')) {
                idGroupItemsCheckedList.push($(this).val());
            }
        });

        if (!idGroupItemsCheckedList.length) {
            idGroupItemsCheckedList = idGroupItemsFullList;
        }

        var finalUrl = formAction + '&' + $.param({items: idGroupItemsCheckedList});

        $(this).prop('disabled', true).addClass('disabled');
        $form.attr('action', finalUrl);
        $form.submit();
    });

    $('.item-check').click(function(){
        var $table = $(this).closest('table');
        var $checkAllOrders = $table.find('.check-all-orders');
        var countChecked = $table.find('.item-check[type="checkbox"]:checked').length;
        var totalCheckboxItems = $table.find('.item-check').length;

        if (totalCheckboxItems === countChecked) {
            $checkAllOrders.prop('checked', true);

            return;
        }

        $checkAllOrders.prop('checked', false);
    });

    $('.more-attributes').click(function(e){
        e.preventDefault();
        var idProductItem = $(this).data('id');
        var $attributes = $('#attribute_details_' + idProductItem);
        var $button = $('#attribute-details-btn-' + idProductItem);
        var isHidden = $attributes.hasClass('hidden');

        $attributes.toggleClass('hidden', !isHidden);
        $button.toggleClass('is-hidden', !isHidden);
        $button.toggleClass('is-shown', isHidden);
    });

    $('.more-history').click(function(e){
        e.preventDefault();
        var idProductItem = $(this).data('id');
        var $history = $('#history_details_' + idProductItem);
        var $button = $('#history-btn-' + idProductItem);
        var isHidden = $history.hasClass('hidden');

        $history.toggleClass('hidden', !isHidden);
        $button.toggleClass('is-hidden', !isHidden);
        $button.toggleClass('is-shown', isHidden);
    });

    $('.item-split').click(function(e){
        e.preventDefault();
        var theID = $(this).data('id');

        $('#split_form_row_' + theID).toggle();
    });

    $('.check-all-orders').click(function(){
        $(this).closest('table').find('.item-check').prop('checked', $(this).prop('checked'));
    });
});
