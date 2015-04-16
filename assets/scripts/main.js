'use strict';

var $ = require('jquery'),
    spinner = require('./spinner'),
    cart = require('./shopping-cart');

$(function() {
  spinner.init($('.spinner'));
  cart.init();
});
