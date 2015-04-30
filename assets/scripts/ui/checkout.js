'use strict';

var $ = require('jquery');

module.exports = {

  init: function() {
    $('.login__skip').click(function() {
      $('.js-checkout-address').removeClass('js-checkout-collapsed');
      $('.js-checkout-login').addClass('js-checkout-collapsed');
    });

    $('.js-delivery-address-checkbox').click(function() {
      if ($('.js-delivery-address-checkbox:checked').length) {
        $('.js-delivery-address').show(300);
        $('.js-invoice-address').attr('placeholder', 'Rechnungsadresse');
      } else {
        $('.js-delivery-address').hide(300);
        $('.js-invoice-address').attr('placeholder', 'Rechnungs- und Lieferadresse');
      }
    });

    $('.js-address-button').click(function() {
      // validate!
      $('.js-checkout-address').addClass('js-checkout-collapsed js-checkout-completed');
      $('.js-checkout-payment').removeClass('js-checkout-collapsed');
    });

    $('.js-payment-button').click(function() {
      // no need to validate
      $('.js-checkout-payment').addClass('js-checkout-collapsed js-checkout-completed');
      $('.js-checkout-confirm').removeClass('js-checkout-collapsed');
    });
  }
};