'use strict';

var $ = require('jquery'),
    scrollPosition = 0;

module.exports = {

  show: function() {
    scrollPosition = $(window).scrollTop();
    $(window).scrollTop(0);
    $('html').toggleClass('overlay-visible');
  },

  hide: function() {
    $('html').toggleClass('overlay-visible');
    $(window).scrollTop(scrollPosition);
    scrollPosition = 0;
  }
};

