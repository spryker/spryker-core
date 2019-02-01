/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function () {
    $('.clear-fields').click(function (e) {
        var subform = $($(this).data('tab'));
        var elements = subform.find('input, textarea, select');

        for(var i=0; i < elements.length; i++) {
            var fieldType = elements[i].type.toLowerCase();

            switch(fieldType) {
                case "text":
                case "password":
                case "email":
                case "tel":
                case "textarea":
                    elements[i].value = "";
                    break;

                case "radio":
                case "checkbox":
                    if (elements[i].checked) {
                        elements[i].checked = false;
                    }
                    break;

                case "select-one":
                case "select-multi":
                    elements[i].selectedIndex = -1;
                    break;

                default:
                    break;
            }
        }
    });
});
