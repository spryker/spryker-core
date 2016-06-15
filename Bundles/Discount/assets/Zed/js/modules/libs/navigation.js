'use strict';

module.exports = function(){
    var activePosition = 0;
    var navigationTabs = $('.tabs-manager > ul.nav-tabs > li');
    navigationTabs.each(function(index, element){
        if ($(element).hasClass('active')) {
            activePosition = index;
        }
    });

    if (activePosition === 0) {
        $('#btn-tab-previous').addClass('hidden');
        $('#btn-tab-next').removeClass('hidden');
    } else  if (activePosition === (navigationTabs.length - 1)) {
        $('#btn-tab-previous').removeClass('hidden');
        $('#btn-tab-next').addClass('hidden');
    } else {
        $('#btn-tab-previous').removeClass('hidden');
        $('#btn-tab-next').removeClass('hidden');
    }
};
