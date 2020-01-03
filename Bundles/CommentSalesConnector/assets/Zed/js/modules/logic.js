'use strict';

var scrollDownComments = function() {
    var commentWrapper = document.getElementsByClassName('comment-wrapper')[0];
    var commentWrapperScrollHeight = commentWrapper.scrollHeight;
    var commentWrapperClientHeight = commentWrapper.clientHeight;
    var scrollTop = commentWrapperScrollHeight - commentWrapperClientHeight;

    $(commentWrapper).animate({ scrollTop: scrollTop }, 1000);
}

$(document).ready(function() {
    scrollDownComments();
});
