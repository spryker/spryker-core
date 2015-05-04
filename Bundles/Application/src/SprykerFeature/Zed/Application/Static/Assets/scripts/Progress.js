'use strict';

var _id = 0;
var _instance = {};

var _active   = {};
var _complete = {};
var _distance = {};
var _progress = {};
var _fraction = {};

var _transitionFn = {};



function _onTransitionEnd(e) {
	/* jshint validthis: true */

	var id = this.id, node = _instance[id].node;

	_complete[id] = false;

	node.removeEventListener('transitionend', _transitionFn[id], false);
	node.classList.remove('active', 'complete');
}



/**
 * Constructor
 * @class Progress component
 * @param {HTMLElement} node The progress indicator base element
 * @returns {Progress}
 * @throws {TypeError} if <code>node</code> is not a <code>HTMLElement</code> instance
 */
function Progress(node) {
	if (!(node instanceof HTMLElement)) throw new TypeError();

	var id = (_id++).toString();

	_instance[id] = this;

	_active[id]   = false;
	_complete[id] = false;
	_distance[id] = 0;
	_progress[id] = 0;
	_fraction[id] = 0.0;

	_transitionFn[id] = _onTransitionEnd.bind(this);


	/**
	 * The instance id
	 * @name id
	 * @memberOf Progress#
	 * @readonly
	 * @type String
	 */
	Object.defineProperty(this, 'id', {
		value : id,
		configurable : true,
		enumerable : true
	});

	/**
	 * The associated element node
	 * @name node
	 * @memberOf Progress#
	 * @readonly
	 * @type HTMLElement
	 */
	Object.defineProperty(this, 'node', {
		value : node,
		configurable : true,
		enumerable : true
	});
}


/**
 * Redefines the instance
 * @param {HTMLElement} node The Progress indicator base element
 * @returns {Progress}
 */
Progress.prototype.define = function(node) {
	if (this.id !== undefined) this.undefine();

	Progress.call(this, node);

	return this;
};

/**
 * Undefines the instance
 * @returns {undefined}
 */
Progress.prototype.undefine = function() {
	var id = this.id;

	if (this.id === undefined) return;

	delete _instance[id];

	delete _active[id];
	delete _complete[id];
	delete _distance[id];
	delete _progress[id];
	delete _fraction[id];

	delete _transitionFn[id];


	Object.defineProperty(this, 'id', {
		value : undefined
	});

	Object.defineProperty(this, 'node', {
		value : null
	});
};


/**
 * <code>true</code> if the progress indicator is active, <code>false</code> otherwise
 * @name active
 * @memberOf Progress#
 * @readonly
 * @type Boolean
 */
Object.defineProperty(Progress.prototype, 'active', {
	get : function() {
		return this.id !== undefined ? _active[this.id] : false;
	},
	configurable : true,
	enumerable : true
});

/**
 * <code>true</code> if the progress indicator is in completed state, <code>false</code> otherwise
 * @name complete
 * @memberOf Progress#
 * @readonly
 * @type Boolean
 */
Object.defineProperty(Progress.prototype, 'complete', {
	get : function() {
		return this.id !== undefined ? _complete[this.id] : false;
	},
	configurable : true,
	enumerable : true
});

/**
 * The instance progress as a fractional float
 * @name progress
 * @memberOf Progress#
 * @readonly
 * @type Float
 */
Object.defineProperty(Progress.prototype, 'progress', {
	get : function() {
		return this.id !== undefined ? _fraction[this.id] : 0.0;
	},
	configurable : true,
	enumerable : true
});


/**
 * Increases the progress distance by <code>code</code>
 * @param {Uint} count The distance increment
 * @returns {Progress}
 * @throws {TypeError} if <code>count</code> is not a <code>Uint</code>
 */
Progress.prototype.addDistance = function(count) {
	if (typeof count !== 'number' || count < 0 || count << 0 !== count) throw new TypeError();

	var id = this.id, node = this.node;

	if (id === undefined) return this;

	if (!_active[id]) {
		_active[id] = true;

		node.classList.add('active');
	}

	if (_complete[id]) {
		_complete[id] = false;

		node.removeEventListener('transitionend', _transitionFn[id], false);
		node.classList.remove('complete');
	}

	_distance[id] += count;
	_fraction[id]  = _progress[id] / _distance[id];

	node.style.width = Math.round(50.0 + _fraction[id] * 50.0) + "%";

	return this;
};

/**
 * Increases the progress by <code>count</code>
 * @param {Uint} count The progress increment
 * @returns {Progress}
 * @throws {TypeError} if <code>count</code> is not a <code>Uint</code>
 */
Progress.prototype.addProgress = function(count) {
	if (typeof count !== 'number' || count < 0 || count << 0 !== count) throw new TypeError();

	var id = this.id;

	if (id === undefined) return this;

	_progress[id] += count;
	_fraction[id]  = _progress[id] / _distance[id];

	if (_fraction[id] === 1.0) this.finish();
	else this.node.style.width = Math.round(50.0 + _fraction[id] * 50.0) + "%";

	return this;
};

/**
 * Completes the progress
 * @returns {Progress}
 */
Progress.prototype.finish = function() {
	var id = this.id, node = this.node;

	if (id === undefined) return;

	_active[id]   = false;
	_complete[id] = true;
	_distance[id] = _progress[id] = 0;
	_fraction[id] = 0.0;

	node.style.width = null;
	node.classList.add('complete');

	node.addEventListener('transitionend', _transitionFn[id], false);

	return this;
};


/**
 * Returns a string representation of the instance
 * @returns {String}
 */
Progress.prototype.toString = function() {
	return Progress.toString() + " " + this.id;
};


/**
 * Returns a type string
 * @returns {string}
 */
Progress.toString = function() {
	return "[Progress]";
};



module.exports = Progress;