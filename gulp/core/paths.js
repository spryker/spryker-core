'use strict';

var _path = require('path');



var _resource = {
	css : 'styles',
	js  : 'scripts',
	svg : 'sprite',
	img : 'images',
	fnt : 'fonts'
};

var _target = {
	src  : 'Assets',
	dst  : 'Public'
};


module.exports = function _getPaths(bundle, target, resource, glob) {
	if (
		typeof bundle !== 'string' || bundle === '' ||
		!(target in _target) ||
		!(resource in _resource) ||
		typeof glob !== 'string'
	) throw new TypeError();

	return _path.join(bundle, _target[target], _resource[resource], glob);
};

module.exports.resource = _resource;
module.exports.target   = _target;