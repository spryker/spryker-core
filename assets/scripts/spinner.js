'use strict';

module.exports = {

  init: function($el) {
    var $incButton = $el.find('.js-spinner__increment'),
        $decButton = $el.find('.js-spinner__decrement'),
        $numberField = $el.find('.js-spinner__number');

    $incButton.click(function() {
      $numberField.val(parseInt($numberField.val())+1);
    });

    $decButton.click(function() {
      var val = $numberField.val();
      if (val > 1) {
        $numberField.val(val-1);
      }
    });
  }
};