'use strict';

var $ = require('jquery'),
    spinner = require('./spinner'),
    search = require('./search'),
    cart = require('./shopping-cart'),
    overlay = require('./overlay'),
    loginForm = require('./login-form');

$(function() {
  spinner.init();
  search.init();
  cart.init();
  overlay.init();
  loginForm.init();
});
