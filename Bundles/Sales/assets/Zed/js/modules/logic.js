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

        disableTrigger($(this));
    });

    $('.trigger-order-event').click(function(e){
        e.preventDefault();

        var $item = $(this);

        disableTrigger($item);
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
