'use strict';

function Ibox() {
    this.selector = '.ibox.nested';

    this.checkErrors();
}

Ibox.prototype.checkErrors = function() {
    var self = this;

    $(self.selector + '[data-auto-errors="true"]').each(function(i, element) {
        var hasError = $(element).find('.has-error').length;

        if (hasError) {
            $(element).addClass('error');
        } else {
            $(element).removeClass('error');
        }
    });
};

module.exports = Ibox;
