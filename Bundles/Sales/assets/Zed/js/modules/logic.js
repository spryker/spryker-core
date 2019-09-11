/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $('.sales-order-item-group-element button').click(function (e) {
        e.preventDefault();
        var keyItemGroup = $(this).closest('.sales-order-item-group-element').data('group-key');
        var $shipmentTable = $('.shipment-item-table-' + keyItemGroup);
        var $idOrderItems = $shipmentTable.find('input[name="order-item"]');
        var idOrderItemsCheckedList = [];
        var idOrderItemsFullList = [];
        var $form = $(this).closest('form');
        var formAction = $form.attr('action');

        $idOrderItems.each(function () {
            idOrderItemsFullList.push($(this).val());

            if ($(this).prop('checked')) {
                idOrderItemsCheckedList.push($(this).val());
            }
        });

        if (!idOrderItemsCheckedList.length) {
            idOrderItemsCheckedList = idOrderItemsFullList;
        }

        var finalUrl = formAction + '&' + $.param({items: idOrderItemsCheckedList});

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
