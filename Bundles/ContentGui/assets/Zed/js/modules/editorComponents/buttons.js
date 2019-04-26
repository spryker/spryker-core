const ContentItemDropdownButton = function (
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

module.exports = {
    ContentItemDropdownButton: ContentItemDropdownButton
};
