'use strict';

var MenuGroup = require('./MenuGroup.js');
var MenuLink  = require('./HotkeyLink.js');



var _id       = 0;
var _instance = {};

var _state  = {};
var _group  = {};
var _link   = {};

var _windowKeyPressFn = {};
var _nodeKeyPressFn   = {};



function _onWindowKeyPress(e) {
	/* jshint validthis: true */

	var code = e.keyCode;
	var char = String.fromCharCode(e.charCode);

	if (code === 27) this.open = !this.open;		//ESC
	else if (
		(e.metaKey || e.ctrlKey) &&
		e.altKey &&
		char.search(/^[A-Z0-9a-z]$/) !== -1 &&
		MenuLink.activateByKey(char)
	) e.preventDefault();
}

function _onNodeKeyPress(e) {

}


function _isLocalOpen() {
	var state = localStorage.getItem('menu.' + this.name + '.open');

	return state !== null ? state === "open" : true;
}

function _setLocalOpen(b) {
	localStorage.setItem('menu.' + this.name + '.open', b ? "open" : "closed");
}


/**
 * Constructor
 * @class Menu component
 * @param {HTMLElement} node The Menu base element
 * @returns {Menu}
 * @throws {TypeError} if <code>node</code> is not a <code>HTMLElement</code> instance
 */
function Menu(name, node) {
	if (
		typeof name !== 'string' || name === "" ||
		!(node instanceof HTMLElement)
	) throw new TypeError();

	var id = (++_id).toString();

	/**
	 * The instance id
	 * @name id
	 * @memberOf Menu
	 * @readonly
	 * @type String
	 */
	Object.defineProperty(this, 'id', {
		value : id,
		configurable : true,
		enumerable : true
	});

	/**
	 * The instance name
	 * @name name
	 * @memberOf Menu
	 * @readonly
	 * @type String
	 */
	Object.defineProperty(this, 'name', {
		value : name,
		configurable : true,
		enumerable : true
	});

	/**
	 * The associated element node
	 * @name node
	 * @memberOf Menu
	 * @readonly
	 * @type HTMLElement
	 */
	Object.defineProperty(this, 'node', {
		value : node,
		configurable : true,
		enumerable : true
	});

	_instance[id] = this;

	_state[id] = 0x1;
	_group[id] = MenuGroup.fromSelector(node.querySelectorAll("li.group"));
	_link[id]  = MenuLink.fromSelector(node.querySelectorAll("a[data-hotkey]"));

	_windowKeyPressFn[id] = _onWindowKeyPress.bind(this);
	_nodeKeyPressFn[id]   = _onNodeKeyPress.bind(this);

	this.open = _isLocalOpen.call(this);

	window.addEventListener('keypress', _windowKeyPressFn[id], false);
	node.addEventListener('keypress', _nodeKeyPressFn[id], false);
}

/**
 * Redefines the instance
 * @param {HTMLElement} node The Menu base element
 * @returns {Menu}
 */
Menu.prototype.define = function(name, node) {
	if (this.id !== undefined) this.undefine();

	Menu.call(this, name, node);

	return this;
};

/**
 * Undefines the instance
 * @returns {undefined}
 */
Menu.prototype.undefine = function() {
	if (this.id === undefined) return;

	var id = this.id, node = this.node;

	_setLocalOpen.call(this, (_state[id] & 0x2) !== 0x0);

	Object.defineProperty(this, 'id', {
		value : undefined
	});

	Object.defineProperty(this, 'name', {
		value : undefined
	});

	Object.defineProperty(this, 'node', {
		value : null
	});

	delete _instance[id];
	delete _state[id];

	_group[id].forEach(function(item, index, source) {
		item.undefine();
	});

	_link[id].forEach(function(item, index, source) {
		item.undefine();
	});

	delete _group[id];
	delete _link[id];

	window.removeEventListener('keypress', _windowKeyPressFn[id], false);
	node.removeEventListener('keypress', _nodeKeyPressFn[id], false);

	delete _windowKeyPressFn[id];
	delete _nodeKeyPressFn[id];
};


/**
 * The contained submenu groups
 * @name groups
 * @memberOf Menu#
 * @readonly
 * @type Array
 */
Object.defineProperty(Menu.prototype, 'groups', {
	get : function() {
		return this.id !== undefined ? _group[this.id].slice(0) : [];
	},
	enumerable : true,
	configurable : true
});


/**
 * <code>true</code> if the menu is opened, <code>false</code> otherwise
 * @name open
 * @memberOf Menu#
 * @readonly
 * @type Boolean
 */
Object.defineProperty(Menu.prototype, 'open', {
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

		}
		else {
			_state[id] &= ~0x2;

			this.node.classList.remove('open');
		}

		return this;
	},
	enumerable : true,
	configurable : true
});


/**
 * Returns <code>true</code> if the instance has a named group <code>name</code>, <code>false</code> otherwise
 * @param {String} name The group name
 * @returns {Boolean}
 */
Menu.prototype.hasGroup = function(name) {

};

/**
 * Returns the named group referenced by <code>name</code>
 * @param {String} name The group name
 * @returns {MenuGroup}
 */
Menu.prototype.getGroup = function(name) {

};


/**
 * Opens the named group referenced by <code>name</code>
 * @param {String} name The group name
 * @returns {Menu}
 */
Menu.prototype.openGroup = function(name) {

};

/**
 * Closes the named group referenced by <code>name</code>
 * @param {String} name The group name
 * @returns {Menu}
 */
Menu.prototype.closeGroup = function(name) {

};


/**
 * Returns a string representation of the instance
 * @returns {String}
 */
Menu.prototype.toString = function() {
	return Menu.toString() + " " + this.id;
};


/**
 * Returns a type string
 * @returns {String}
 */
Menu.toString = function() {
	return '[Menu]';
};



module.exports = Menu;