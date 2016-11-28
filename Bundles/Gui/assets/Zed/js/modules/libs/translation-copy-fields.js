'use strict';

/**
 * Copy the value of a field of a special data attribute to all fields with the same data attribute.
 *
 * Usage in templates:
 *     <input type="text" data-translation-key="my_translation_key" />
 */
function TranslationCopyFields() {
    this.translationDataAttributeName = 'data-translation-key';
    this.translationDataAttribute = 'translationKey';

    this.addCopyButtons();
}

/**
 * Add copy buttons for fields that has the necessary data attribute
 */
TranslationCopyFields.prototype.addCopyButtons = function() {
    var self = this;

    $('input[' + this.translationDataAttributeName + ']').each(function(i, field) {
        var copyButton = $('<button type="button" class="btn btn-primary" data-style="zoom-in" title="Copy to other languages"><span class="fa fa-copy"></span></button>');

        if ($(field).parent().hasClass('input-group')) {
            return;
        }

        copyButton.on('click', function() {
            self.copy($(this), field);
        });

        $(field)
            .wrap('<div class="input-group"></div>')
            .after($('<span class="input-group-btn"></span>').append(copyButton));
    });
};

/**
 * Copy the value of the field on button click.
 *
 * @param button
 * @param field
 */
TranslationCopyFields.prototype.copy = function(button, field) {
    var self = this;
    var selector = 'input[' + self.translationDataAttributeName + '="' + $(field).data(self.translationDataAttribute) + '"]';

    $(selector).val(field.value);

    button.find('span')
        .removeClass('fa-copy')
        .addClass('fa-check');

    setTimeout(function() {
        button.find('span')
            .addClass('fa-copy')
            .removeClass('fa-check');
    }, 1000);
};

module.exports = TranslationCopyFields;
