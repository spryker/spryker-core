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

/**
 * @deprecated not used any more
 */
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

/**
 * @deprecated not used any more
 */
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
    $('.trigger-event').click(function (e) {
        e.preventDefault();

        $(this).prop('disabled', true).addClass('disabled');

        var $form = $(this).closest('form');
        var formAction = $form.attr('action');
        var finalUrl = formAction + '&' + $.param({items: getSelectedItems()});

        $form.attr('action', finalUrl);

        $(this).parents('form').first().submit();
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
