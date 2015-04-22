'use strict';

/**
 * @ngdoc module
 * @name spyTemplate
 */
require('Ui').ng
	.module('spyTemplate', ['spyBase'])
	.run(['$templateCache', function(t) {

	}]);



module.exports.events = require('./event/TemplateEvent');

require('./service/TemplateModelService');

require('./controller/TemplateController');

require('./directive/spyTemplate');
require('./directive/spyTemplateRead');
require('./directive/spyTemplateError');