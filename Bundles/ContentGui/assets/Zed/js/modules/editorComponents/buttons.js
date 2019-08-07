/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ContentItemDropdownButton = function (
    buttonTitle,
    dropdownList,
    dropdownCallback
) {
    return function (context) {
        var ui = $.summernote.ui;

        var button = ui.buttonGroup([
            ui.button({
                contents: buttonTitle + ' <i class="fa fa-caret-down" aria-hidden="true"></i>',
                data: {
                    toggle: 'dropdown'
                }
            }),
            ui.dropdown({
                contents: dropdownList,
                click: dropdownCallback(context)
            })
        ]);

        return button.render();
    }
};

var PopoverButton = function (buttonContent, buttonCallback) {
    return function (context) {
        var ui = $.summernote.ui;

        var button = ui.button({
            contents: buttonContent.icon + ' ' + buttonContent.title,
            tooltip: buttonContent.title,
            click: buttonCallback(context)
        });

        return button.render();
    }
};

module.exports = {
    ContentItemDropdownButton: ContentItemDropdownButton,
    PopoverButton: PopoverButton
};
