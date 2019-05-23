'use strict';

function getSelectedItems(idOrderItem) {
    var selectedItems = [];

    if (parseInt(idOrderItem) > 0) {
        selectedItems.push(idOrderItem);

        return selectedItems;
    }

    $('.item-check').each(function() {
        if ($(this).prop('checked') === true) {
            selectedItems.push($(this).val());
        }
    });

    return selectedItems;
}

function createTriggerUrl(idOrder, idReclamation, eventName) {
    var url = '/oms/trigger/trigger-event-for-order';
    var parameters = {
        event: eventName,
        'id-sales-order': idOrder,
        redirect: '/sales-reclamation-gui/detail?id-reclamation=' + idReclamation
    };

    parameters.items = getSelectedItems();

    if (!isSpecificItemsSelected(parameters)) {
        parameters = expandParametersWithClaimedOrderItems(parameters);
    }

    var finalUrl = url + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}

function isSpecificItemsSelected(parameters) {
    return parameters.items.length > 0;
}

function expandParametersWithClaimedOrderItems(parameters) {
    $('.item-check').each(function() {
        parameters.items.push($(this).val());
    });

    return parameters;
}

function createTriggerItemUrl(idOrder, idOrderItem, idReclamation, eventName) {
    var url = '/oms/trigger/trigger-event-for-order-items';
    var parameters = {
        event: eventName,
        'id-sales-order-item': idOrderItem,
        redirect: '/sales-reclamation-gui/detail?id-reclamation=' + idReclamation
    };

    parameters.items = getSelectedItems();

    var finalUrl = url + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}

function disableTrigger($item) {
    $item
        .prop('disabled', true)
        .addClass('disabled');
}

$(document).ready(function() {
    $('.trigger-order-single-event').click(function(e) {
        e.preventDefault();
        var $item = $(this);

        disableTrigger($item);

        var idOrder = $item.data('id-sales-order');
        var idReclamation = $item.data('id-reclamation');
        var eventName = $item.data('event');
        var idOrderItem = $item.data('id-item');

        window.location = createTriggerItemUrl(idOrder, idOrderItem, idReclamation, eventName);
    });

    $('.trigger-order-event').click(function(e) {
        e.preventDefault();

        var $item = $(this);

        disableTrigger($item);

        var idOrder = $item.data('id-sales-order');
        var idReclamation = $item.data('id-sales-reclamation');
        var eventName = $item.data('event');

        window.location = createTriggerUrl(idOrder, idReclamation, eventName);
    });

    $('.more-history').click(function(e) {
        e.preventDefault();
        var idProductItem = $(this).data('id');
        var $history = $('#history_details_' + idProductItem);
        var $button = $('#history-btn-' + idProductItem);
        var isHidden = $history.hasClass('hidden');

        $history.toggleClass('hidden', !isHidden);
        $button.toggleClass('is-hidden', !isHidden);
        $button.toggleClass('is-shown', isHidden);
    });

    $('.item-check').click(function() {
        var countChecked = $(".item-check[type='checkbox']:checked").length;
        var totalCheckboxItems = $('.item-check').length;

        if (totalCheckboxItems === countChecked) {
            $('#check-all-orders').prop('checked', true);

            return true;
        }

        $('#check-all-orders').prop('checked', false);

        return true;
    });

    $('#check-all-orders').click(function() {
        if ($(this).prop('checked') === true) {
            var checked = true;
        } else {
            var checked = false;
        }

        $('.item-check').each(function() {
            $(this).prop('checked', checked);
        });
    });
});
