'use strict';

var $ = require('jquery'),
    _ = require('underscore'),
    cartData = require('../data/cart'),
    overlay = require('./overlay'),
    templateSrc = require('../templates/cart-item'),
    template,
    $cart,
    isExpanded = false;

var showCart = function() {
  $cart.addClass('cart--expanded');
  overlay.show();
  isExpanded = true;
};

var hideCart = function() {
  $cart.removeClass('cart--expanded');
  overlay.hide();
  isExpanded = false;
};

var renderCart = function() {
  var html = '';
  var $el = $('.js-cart-items');
  var $shipping = $('.js-cart-shipping');
  var $total = $('.js-cart-total');
  _.each(cartData.cart.items, function(item) {
    html += template(item);
  });
  $el.html(html);
  $shipping.html('€ '+cartData.cart.shipping);
  $total.html('€ '+cartData.cart.total);
}

module.exports = {

  init: function() {
    $cart = $('.js-shopping-cart');
    template = _.template(templateSrc);

    cartData.loadCart()
      .done(function(data) {
        console.log(data);
        renderCart();
      });

    $('.js-cart-toggle').on('click', function() {
      if (isExpanded) {
        hideCart();
      } else {
        showCart();
      }
    });
    $('.js-cart-close').on('click', hideCart);
  }
};
