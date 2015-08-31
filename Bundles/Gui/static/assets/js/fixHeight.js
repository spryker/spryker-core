/**
 * get the  highest height value of all elements and change them to be equal
 */
(function($){
    'use strict';

    $.fn.sprykerFixHeight = function(){
        var self = this;
        var maxHeight = 0;

        self.getFullHeight = function(element) {
            return element.outerHeight();
        };

        self.calculateElementCoreHeight = function(element){
            return parseInt(maxHeight - self.getElementExtraHeight(element));
        };

        self.getElementExtraHeight = function(element){
            return self.getFullHeight(element) - element.height();
        };

        self.changeHeight = function(element){
            element.each(function(){
                var newHeight = self.calculateElementCoreHeight($(this));
                $(this).height(newHeight + 'px');
            });
        };

        self.calculateHeight = function(element){
            element.each(function(){
                var elementHeight = self.getFullHeight($(this));
                if (elementHeight > maxHeight) {
                    maxHeight = elementHeight;
                }
            });
            self.changeHeight(element);
        };

        self.calculateHeight(self);
    };
}(jQuery));
