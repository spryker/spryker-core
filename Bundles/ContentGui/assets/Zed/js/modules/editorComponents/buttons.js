const ContentItemDropdownButton = function (
    buttonContents,
    dropdownList,
    dropdownCallback
) {
    return function (context) {
        var ui = $.summernote.ui;

        var button = ui.buttonGroup([
            ui.button({
                contents: buttonContents,
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
}

module.exports = {
    ContentItemDropdownButton: ContentItemDropdownButton
}
