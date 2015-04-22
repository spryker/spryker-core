'use strict';

/**
 * @ngdoc module
 * @name spyAction
 */
require('Ui').ng
	.module('spyAction', []);


module.exports.events = require('./event/ActionEvent');

require('./service/ActionModelService');

require('./directive/spyAction');