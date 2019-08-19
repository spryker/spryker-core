'use strict';

function getSelectedItems(idOrderItem) {
    var selectedItems = [];

    if (parseInt(idOrderItem) > 0) {
        selectedItems.push(idOrderItem);

        return selectedItems;
    }

    $('.item-check').each(function(){
        if ($(this).prop('checked') === true) {
            selectedItems.push($(this).val());
        }
    });

    return selectedItems;
}

/**
 * @deprecated not used any more
 */
function createTriggerUrl(idOrder, eventName) {
    var url = '/oms/trigger/trigger-event-for-order';
    var parameters = {
        event: eventName,
        'id-sales-order': idOrder,
        redirect: '/sales/detail?id-sales-order=' + idOrder
    };

    parameters.items = getSelectedItems();

    var finalUrl = url + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}

/**
 * @deprecated not used any more
 */
function createTriggerItemsUrl(idOrder, idOrderItems, eventName) {
    var url = '/oms/trigger/trigger-event-for-order-items';
    var parameters = {
        event: eventName,
        redirect: '/sales/detail?id-sales-order=' + idOrder,
        items: idOrderItems,
    };

    parameters.items = getSelectedItems();

    var finalUrl = url + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}

/**
 * @deprecated not used any more
 */
function disableTrigger($item) {
    $item
        .prop('disabled', true)
        .addClass('disabled');
}

$(document).ready(function () {
    $('.trigger-event').click(function (e) {
        e.preventDefault();

        $(this).prop('disabled', true).addClass('disabled');

        var $form = $(this).closest('form');
        var formAction = $form.attr('action');
        var finalUrl = formAction + '&' + $.param({items: getSelectedItems()});

        $form.attr('action', finalUrl);

        $(this).parents('form').first().submit();
        var $item = $(this);

        disableTrigger($item);

        var idOrder = $item.data('id-sales-order');
        var eventName = $item.data('event');
        var idOrderItem = $item.data('id-item');

        window.location = createTriggerItemsUrl(idOrder, [idOrderItem], eventName);
    });

    $('.trigger-order-event-for-all-items').click(function (e) {
        e.preventDefault();

        var $item = $(this);

        disableTrigger($item);

        var idOrder = $item.data('id-sales-order');
        var eventName = $item.data('event');

        window.location = createTriggerUrl(idOrder, eventName);
    });

    $('.trigger-order-event').click(function (e) {
        e.preventDefault();

        var $item = $(this);

        disableTrigger($item);

        var idOrder = $item.data('id-sales-order');
        var eventName = $item.data('event');
        var idShipment = $item.data('id-sales-shipment');
        var $shipmentTable = $('.shipment-item-table-' + idShipment);
        var $idOrderItems = $shipmentTable.find('input[name="order-item"]');
        var idOrderItemsCheckedList = [];
        var idOrderItemsFullList = [];

        $idOrderItems.each(function () {
            idOrderItemsFullList.push($(this).val());

            if ($(this).prop('checked')) {
                idOrderItemsCheckedList.push($(this).val());
            }
        });

        if (!idOrderItemsCheckedList.length) {
            idOrderItemsCheckedList = idOrderItemsFullList;
        }

        window.location = createTriggerItemsUrl(idOrder, idOrderItemsCheckedList, eventName);
    });

    $('.item-check').click(function(){
        var countChecked = $(".item-check[type='checkbox']:checked").length;
        var totalCheckboxItems = $('.item-check').length;

        if (totalCheckboxItems === countChecked) {
            $('#check-all-orders').prop('checked', true);

            return true;
        }

        $('#check-all-orders').prop('checked', false);

        return true;
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
