/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

(function($){
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
