'use strict';

var _id       = 0;
var _instance = {};

var _state      = {};

var _label      = {};
var _list       = {};
var _onClickIns = {};



function _onClick(e) {
	/* jshint validthis: true*/
	this.open = !this.open;
}


function _openGroup(node) {
	/* jshint validthis: true*/

	var _cb = function(e) {
		if (e.target !== node || e.propertyName !== 'height') return;

		node.removeEventListener('transitionend', _cb, false);
		node.style.height = "auto";
	}.bind(this);

	node.addEventListener('transitionend', _cb, false);
	node.style.height = node.scrollHeight + "px";
}

function _closeGroup(node) {
	node.style.height = node.clientHeight + "px";

	window.setTimeout(function() {
		node.style.height = "";
	}, 20);
}


/**
 * Constructor
 * @class Menu group component
 * @param {HTMLElement} node The menu group element
 * @returns {MenuGroup}
 * @throws {TypeError} if <code>node</code> is not a <code>HTMLElement</code> instance
 * @throws {TypeError} if <code>node</code> does not contain a childlist and label
 */
function MenuGroup(node) {
	var id = (++_id).toString();

	if (!(node instanceof HTMLElement)) throw new TypeError();

	var label = node.querySelector("span");
	var list  = node.querySelector("ul");

	if (label === null || list === null) throw new TypeError();

	/**
	 * The instance id
	 * @name id
	 * @memberOf MenuGroup#
	 * @readonly
	 * @type {String}
	 */
	Object.defineProperty(this, 'id', {
		value : id,
		configurable : true,
		enumerable : true
	});

	/**
	 * The group name
	 * @name name
	 * @memberOf MenuGroup#
	 * @readonly
	 * @type {String}
	 */
	Object.defineProperty(this, 'name', {
		value : null,
		configurable : true,
		enumerable : true
	});

	/**
	 * The group Element
	 * @name node
	 * @memberOf MenuGroup#
	 * @readonly
	 * @type {String}
	 */
	Object.defineProperty(this, 'node', {
		value : node,
		configurable : true,
		enumerable : true
	});

	_instance[id]   = this;
	_state[id]      = 0x1;

	_label[id]      = label;
	_list[id]       = list;
	_onClickIns[id] = _onClick.bind(this);

	if (node.classList.contains('active')) this.open = true;

	label.addEventListener('click', _onClickIns[id], false);
}

/**
 * Redefines the instance
 * @param {HTMLElement} node The menu group element
 * @returns {MenuGroup}
 */
MenuGroup.prototype.define = function(node) {
	if (this.id !== undefined) this.undefine();

	MenuGroup.call(this, node);

	return this;
};

/**
 * Undefines the instance
 * @returns {undefined}
 */
MenuGroup.prototype.undefine = function() {
	if (this.id === undefined) return;

	var id = this.id;

	Object.defineProperty(this, 'id', {
		value : undefined
	});

	Object.defineProperty(this, 'name', {
		value : ''
	});

	Object.defineProperty(this, 'node', {
		value : null
	});

	_label[id].removeEventListener('click', _onClickIns[id], false);

	delete _instance[id];
	delete _state[id];

	delete _label[id];
	delete _list[id];
	delete _onClickIns[id];
};


/**
 * <code>true</code> if menu is open, <code>false</code> otherwise
 * @name open
 * @memberOf MenuGroup#
 * @type {Boolean}
 */
Object.defineProperty(MenuGroup.prototype, 'open', {
	get : function() {
		return this.id !== undefined && Boolean(_state[this.id] & 0x2);
	},
	set : function(b) {
		if (typeof b !== 'boolean') throw new TypeError();

		if (this.id === undefined || b === Boolean(_state[this.id] & 0x2)) return this;

		var id = this.id;

		if (b) {
			_state[id] |= 0x2;

			this.node.classList.add('open');

			_openGroup(_list[id]);
		}
		else {
			_state[id] &= ~0x2;

			this.node.classList.remove('open');

			_closeGroup(_list[id]);
		}

		return this;
	},
	configurable : true,
	enumerable : true
});


/**
 * Returns a string representation of the instance
 * @returns {String}
 */
MenuGroup.prototype.toString = function() {
	return MenuGroup.toString() + " " + this.id;
};


/**
 * Returns an <code>Array</code> of instances created from <code>selector</code>
 * @param {NodeList} selector The groups selector
 * @returns {Array}
 * @throws {TypeError} if <code>selector</code> is not a <code>NodeList</code>
 */
MenuGroup.fromSelector = function(selector) {
	if (!(selector instanceof NodeList)) throw new TypeError();

	return Array.prototype
		.slice.call(selector, 0)
		.map(function(item, index, source) {
			return new MenuGroup(item);
		});
};

/**
 * Returns a type string
 * @returns {String}
 */
MenuGroup.toString = function() {
	return '[MenuGroup]';
};



module.exports = MenuGroup;