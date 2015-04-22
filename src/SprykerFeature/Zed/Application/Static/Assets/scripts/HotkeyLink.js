'use strict';

var _id = 0;
var _instance = {};



function HotkeyLink(node) {
	if (!(node instanceof HTMLAnchorElement)) throw new TypeError();

	var key = node.getAttribute('data-hotkey');

	if (key === null || key.length !== 1) throw new Error();

	var id = (++_id).toString();

	Object.defineProperty(this, 'id', {
		value : id,
		configurable : true,
		enumerable : true
	});

	Object.defineProperty(this, 'key', {
		value : key,
		configurable : true,
		enumerable : true
	});

	Object.defineProperty(this, 'node', {
		value : node,
		configurable : true,
		enumerable : true
	});

	_instance[id] = this;
}

HotkeyLink.prototype.define = function(node) {
	if (this.id !== undefined) this.undefine();

	HotkeyLink.call(this, node);

	return this;
};

HotkeyLink.prototype.undefine = function() {
	if (this.id === undefined) return;

	var id = this.id;

	Object.defineProperty(this, 'id', {
		value : undefined
	});

	Object.defineProperty(this, 'key', {
		value : ''
	});

	Object.defineProperty(this, 'node', {
		value : null
	});

	delete _instance[id];
};


HotkeyLink.prototype.activate = function() {
	this.node.click();
};


HotkeyLink.prototype.toString = function() {
	return HotkeyLink.toString() + this.id;
};



HotkeyLink.fromSelector = function(selector) {
	if (!(selector instanceof NodeList)) throw new TypeError();

	return Array.prototype
		.slice.call(selector, 0)
		.map(function(item, index, source) {
			return new HotkeyLink(item);
		});
};


HotkeyLink.activateByKey = function(key) {
	if (typeof key !== 'string' || key.length !== 1) throw new TypeError();

	for (var id in _instance) {
		if (_instance[id].key === key) _instance[id].activate();

		return true;
	}

	return false;
};


HotkeyLink.toString = function() {
	return '[HotkeyLink]';
};



module.exports = HotkeyLink;