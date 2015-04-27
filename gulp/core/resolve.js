'use strict';

var _q = require('q');



/**
 * Returns a promise resolving <code>tasks</code>
 * @param {_q[]}      tasks  The Array of tasks
 * @param {Function} [done]  Called when promise resolves
 * @returns {_q}
 */
module.exports = function _resolveTasks(tasks, done) {
	var q = _q.all(tasks);

	if (typeof done === 'function') q.then(done.bind(null, undefined));

	return q;
};