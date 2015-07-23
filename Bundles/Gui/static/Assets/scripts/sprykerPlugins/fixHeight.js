/**
 * get the  highest height value of all elements and change them to be equal
 */
(function($){
    'use strict';

    $.fn.sprykerFixHeight = function(){
        var maxHeight = 0;
        this.each(function(){
            var elementHeight = $(this).height();
            if (elementHeight > maxHeight) {
                maxHeight = elementHeight;
            }
        });
        this.each(function(){
            $(this).height(maxHeight + 'px');
        });
    };
}(jQuery));
