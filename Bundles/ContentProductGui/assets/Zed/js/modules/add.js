/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $.each($('.add-abstract-product-id'), function(index, subform) {
        var subformList = $($(subform).data('list'));
        $.each(subformList.children(), function(index, element) {
            if (index > 0) {
                addRemoveIdProductAbstractButton(element);
            }
        });
    });

    $('.add-abstract-product-id').click(function (e) {
        var list = $($(this).data('list'));
        var counter = list.data('widget-counter') | list.children().length;
        var newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;
        list.data('widget-counter', counter);
        var newElement = addRemoveIdProductAbstractButton(newWidget);
        list.append(newElement);
    });

    $(document).on("click", ".remove-abstract-product-id", function() {
        $(this).parent().remove();
    });

    function addRemoveIdProductAbstractButton(element) {
        return $(element).append('<i class="fa fa-minus remove-abstract-product-id pull-right"></i>');
    }
});
