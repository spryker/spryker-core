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

function isSpecificItemsSelected(parameters) {
    return parameters.items.length > 0;
}

function expandParametersWithClaimedOrderItems(parameters) {
    $('.item-check').each(function() {
        parameters.items.push($(this).val());
    });

    return parameters;
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
