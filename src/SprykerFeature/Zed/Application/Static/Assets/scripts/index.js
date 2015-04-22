'use strict';

var Menu = require('./Menu.js');



window.addEventListener('DOMContentLoaded', function _onDOM(e) {
	window.removeEventListener('DOMContentLoaded', _onDOM, false);

	var node = document.querySelector('body.spy-page>nav.spy-page-nav');

	if (node === null) return;

	exports.mainMenu = new Menu('main', node);

	node = document.querySelector('body.spy-page>header.spy-page-header nav.menu');

	if (node === null) return;

	node.addEventListener('click', function _onHomeClick(e) {
		exports.mainMenu.open = !exports.mainMenu.open;
	});

	window.addEventListener('unload', function _onUnload(e) {
		window.removeEventListener('unload', _onUnload, false);

		exports.mainMenu.undefine();
	}, false);
}, false);



exports.mainMenu = null;
