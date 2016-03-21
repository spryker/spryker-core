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

function createTriggerItemUrl(idOrder, idOrderItem, eventName) {
    var url = '/oms/trigger/trigger-event-for-order-items';
    var parameters = {
        event: eventName,
        'id-sales-order-item': idOrderItem,
        redirect: '/sales/detail?id-sales-order=' + idOrder
    };

    parameters.items = getSelectedItems();

    var finalUrl = url + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}

var History = new function(){
    var getContainer = function(idProductItem){
        return '#history_details_' + idProductItem;
    };

    var getButton = function(idProductItem){
        return '#history-btn-' + idProductItem;
    };

    var show = function(idProductItem){
        $(getContainer(idProductItem)).removeClass('hidden');
        $(getButton(idProductItem)).removeClass('is-hidden').addClass('is-shown');
    };

    var hide = function(idProductItem){
        $(getContainer(idProductItem)).addClass('hidden');
        $(getButton(idProductItem)).addClass('is-hidden').removeClass('is-shown');
    };

    var isHidden = function(idProductItem){
        return $(getContainer(idProductItem)).hasClass('hidden');
    };

    this.toggle = function(idProductItem){
        if (isHidden(idProductItem)) {
            show(idProductItem);
            return;
        }

        hide(theID);
    };
};

$(document).ready(function() {
    $('.trigger-order-single-event').click(function(e){
        e.preventDefault();

        var idOrder = $(this).data('id-sales-order');
        var eventName = $(this).data('event');
        var idOrderItem = $(this).data('id-item');

        window.location = createTriggerItemUrl(idOrder, idOrderItem, eventName);
    });

    $('.trigger-order-event').click(function(e){
        e.preventDefault();

        var idOrder = $(this).data('id-sales-order');
        var eventName = $(this).data('event');

        window.location = createTriggerUrl(idOrder, eventName);
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

    $('.more-history').click(function(e){
        e.preventDefault();
        var idProductItem = $(this).data('id');

        History.toggle(idProductItem);
    });

    $('.item-split').click(function(e){
        e.preventDefault();
        var theID = $(this).data('id');

        $('#split_form_row_' + theID).toggle();
    });

    $('#check-all-orders').click(function(){
        if ($(this).prop('checked') === true) {
            var checked = true;
        } else {
            var checked = false;
        }

        $('.item-check').each(function(){
            $(this).prop('checked', checked);
        });
    });
});
