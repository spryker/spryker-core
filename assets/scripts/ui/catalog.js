'use strict';

require ('jquery-ui/slider');
var $ = require('jquery');

var updatePriceValues = function(event, ui) {
  var min = ui.values[0];
  var max = ui.values[1];
  $('.js-filter-price-min').html(min);
  $('.js-filter-price-max').html(max);

};

var triggerPriceChange = function(event, ui) {
  $(ui.handle).parents('.js-filter').trigger('change');
}

var initColorFilter = function() {
  $('.js-color-name').each(function(i, el) {
    var color = $(el).siblings(':radio').data('color');
    $(el).css('background-color', color);
  });
};

var initActiveFilterList = function() {
  $('.js-filter').on('change', function(e) {
    updateActiveFilterList($(e.currentTarget));
  });

  $('.js-filter-remove').on('click', function(e) {
    var filterId = $(e.currentTarget).parent().data('filter-id');
    clearFilter(filterId);
  });
};

var updateActiveFilterList = function($changedFilter) {
  var filterId = $changedFilter.data('filter-id');
  var $activeElement = $('.active-filter[data-filter-id="'+filterId+'"');
  var filterValue = getFilterValue($changedFilter);

  $activeElement.attr('data-filter-value', filterValue);
  $activeElement.find('.js-filter-value').text(getFilterValue($changedFilter));
};

var getFilterValue = function($filter) {
  var $selected;
  var text = "";
  var separator = " + "

  if ($filter.find('[type="radio"]').length) {
    $selected = $filter.find('[type="radio"]:checked');
  } else if ($filter.find('[type="checkbox"]').length) {
    $selected = $filter.find('[type="checkbox"]:checked');
  } else if ($filter.find('.ui-slider').length) {
    var values = $filter.find('.ui-slider').slider('values');
    if (values[0] === 0 && values[1] === 200) {
      return '';
    } else {
      return '€ '+values[0]+' - € '+values[1];
    }
  } else {
    // any other kinds of filter required?
  }

  // if it's the default value, don't show an active filter
  if ($selected.hasClass('js-filter-default-value')) {
    return '';
  }

  $selected.each(function() {
    $(this).siblings('label').contents().each(function() {
      if (this.nodeType == Node.TEXT_NODE && this.textContent.trim().length > 0) {
        text += this.textContent + separator;
      }
    });
  });
  return text.substr(0, text.length - separator.length - 1);
};

var clearFilter = function(filterId) {
  var $filter = $('.js-filter[data-filter-id="'+filterId+'"');

  // turn off all checkboxes
  $filter.find('[type="checkbox"]:checked').attr('checked', false);

  // is there a default radio option? if so, select it
  if ($filter.hasClass('js-filter-default')) {
    $filter.find('.js-filter-default-value').prop('checked', true);
  } else {
    $filter.find('[type="radio"]:checked').prop('checked', false);
  }

  // reset price filter
  if ($filter.find('.ui-slider').length) {
    $filter.find('.ui-slider').slider('values', [0, 200]);
    $('.active-filter[data-filter-id="'+filterId+'"]').attr('data-filter-value', '');
    updatePriceValues(null, { values: [0, 200]});

  }

  $filter.trigger('change');
}

module.exports = {
  init: function() {
    $('.js-price-slider').slider({
      range: true,
      max: 200,
      values: [0, 200],
      animate: 'fast',
      slide: updatePriceValues,
      change: triggerPriceChange
    });

    initColorFilter();
    initActiveFilterList();
  }
}