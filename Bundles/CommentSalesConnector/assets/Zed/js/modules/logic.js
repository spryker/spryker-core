'use strict';

var scrollDownComments = function () {
    var commentWrapper = document.getElementsByClassName('comment-wrapper')[0];

    if (!commentWrapper) {
        return;
    }

    $('html, body').animate({ scrollTop: $(commentWrapper).offset().top + 40 + 'px' }, 1000);
};

$(document).ready(function () {
    scrollDownComments();
});
