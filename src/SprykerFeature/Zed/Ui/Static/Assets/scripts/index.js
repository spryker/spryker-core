'use strict';

module.exports.ng = require('./dependencies');



require('./spyBase');
require('./spyFormat');
require('./spyControl');
require('./spyLayout');

module.exports.spyAction   = require('./spyAction');
module.exports.spyTemplate = require('./spyTemplate');
module.exports.spyGrid     = require('./spyGrid');
module.exports.spyForm     = require('./spyForm');

require('./spyFormComponent');
