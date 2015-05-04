'use strict';

var Menu     = require('./Menu');
var Progress = require('./Progress');

var _progress = null;


(function(node) {
	if (node === null) return;

	node.classList.add('progress');

	_progress = new Progress(node);

	window.addEventListener('query-add'    , _progress.addDistance.bind(_progress, 1), false);
	window.addEventListener('query-resolve', _progress.addProgress.bind(_progress, 1), false);
})(document.createElement('section'));



window.addEventListener('DOMContentLoaded', function _onDOM(e) {
	window.removeEventListener('DOMContentLoaded', _onDOM, false);

	(function(node) {
		if (node !== null && _progress !== null) node.appendChild(_progress.node);
	})(document.querySelector('body.spy-page>header.spy-page-header'));

	(function(node) {
		if (node === null) return;

		exports.mainMenu = new Menu('main', node);

		node = document.querySelector('body.spy-page>header.spy-page-header nav.menu');

		if (node === null) return;

		node.addEventListener('click', function _onHomeClick(e) {
			exports.mainMenu.open = !exports.mainMenu.open;
		});

		window.addEventListener('unload', function _onUnload(e) {
			exports.mainMenu.undefine();
		}, false);
	})(document.querySelector('body.spy-page>nav.spy-page-nav'));
}, false);



exports.mainMenu = null;
