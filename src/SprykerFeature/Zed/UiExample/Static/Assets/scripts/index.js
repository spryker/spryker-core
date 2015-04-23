'use strict';

var _hl = require('../../../../../../../../ui/node_modules/highlight.js');



window.addEventListener('DOMContentLoaded', function _onDom (e) {
	window.removeEventListener('DOMContentLoaded', _onDom, false);

	Array.prototype
		.slice.call(document.querySelectorAll('pre code'), 0)
		.forEach(function(item, index, source) {
			_hl.highlightBlock(item);
		});
}, false);