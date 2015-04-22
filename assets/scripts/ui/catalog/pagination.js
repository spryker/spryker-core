'use strict';

var $ = require('jquery'),
    URLManager = require('./URLManager'),
    animationTime = 500;

var page = URLManager.getParam('page') || 1;
var template = '<ul class="catalog__products">'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'  <li class="catalog__product"></li>'+
'</ul>';

var paginate = function(forward) {
  if (!forward && page === 1) {
    return;
  }
  var $current =   $('.js-products-current');
  var $prev =   $('.js-products-prev');
  var $next =   $('.js-products-next');

  if (forward) {
    $prev.remove();
    $current.removeClass('js-products-current').addClass('js-products-prev');
    $next.removeClass('js-products-next')
    window.setTimeout(function() {
      $next.addClass('js-products-current');
    }, animationTime);
    insertNext();
    page++;
  } else {
    $next.remove();
    $current.removeClass('js-products-current').addClass('js-products-next');
    $prev.removeClass('js-products-prev');
    window.setTimeout(function() {
      $prev.addClass('js-products-current');
    }, animationTime);
    insertPrev();
    page--;
  }

  updateURL();
};

var insertNext = function() {
  var $next = $(template);
  $next.addClass('js-products-next js-products-loading js-products-spinning');
  $next.appendTo('.js-products-holder');
  loadProducts('/catalog-mock.html', $next);
};

var insertPrev = function() {
  var $prev = $(template);
  $prev.addClass('js-products-prev js-products-loading js-products-spinning');
  $prev.prependTo('.js-products-holder');
  loadProducts('/catalog-mock.html', $prev);
};

var loadProducts = function(url, $products) {
  window.setTimeout(function() {
    $.ajax({
      url: url
    }).done(function(data) {
      $products.children().remove();
      $(data).appendTo($products);
      $products.removeClass('js-products-spinning');
      window.setTimeout(function() {
        $products.removeClass('js-products-loading');
      }, 200);
    });
  }, 5000);
};

var updateURL = function() {
  var params = URLManager.getParams();
  params.page = page;
  URLManager.setParams(params);
};

module.exports = {
  init: function() {
    $('.js-pagination-prev').click(function(e) {
      $(e.target).attr('disabled', true);
      paginate(false);
      window.setTimeout(function() { $(e.target).attr('disabled', false); }, animationTime)
    });

    $('.js-pagination-next').click(function(e) {
      $(e.target).attr('disabled', true);
      paginate(true);
      window.setTimeout(function() { $(e.target).attr('disabled', false); }, animationTime)
    });
  }

};