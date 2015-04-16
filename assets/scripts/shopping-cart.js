'use strict';

var $ = require('jquery'),
    overlay = require('./overlay');

module.exports = {

  init: function() {
    var $cart = $('.js-shopping-cart'),
        $toggle = $('.js-cart-toggle'),
        isExpanded = false;
    $toggle.on('click', function() {
      if (isExpanded) {
        $cart.removeClass('cart--expanded');
        overlay.hide();
        isExpanded = false;
      } else {
        $cart.addClass('cart--expanded');
        overlay.show();
        isExpanded = true;
      }
    });
  }
};