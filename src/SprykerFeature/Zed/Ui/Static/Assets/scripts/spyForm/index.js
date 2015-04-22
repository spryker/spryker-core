'use strict';

/**
 * @ngdoc module
 * @name spyForm
 */
require('Ui').ng
	.module('spyForm', ['spyBase', 'ngAnimate', 'ngResource'])
	.run(['$templateCache', function(t) {
		var fs = require('fs');

		t.put('spyForm/FieldMessage'   , fs.readFileSync(__dirname + '/template/FieldMessage.html'   , 'utf8'));
		t.put('spyForm/InputText'      , fs.readFileSync(__dirname + '/template/InputText.html'      , 'utf8'));
		t.put('spyForm/InputPassword'  , fs.readFileSync(__dirname + '/template/InputPassword.html'  , 'utf8'));
		t.put('spyForm/InputInteger'   , fs.readFileSync(__dirname + '/template/InputInteger.html'   , 'utf8'));
		t.put('spyForm/InputSelect'    , fs.readFileSync(__dirname + '/template/InputSelect.html'    , 'utf8'));
		t.put('spyForm/InputTextSelect', fs.readFileSync(__dirname + '/template/InputTextSelect.html', 'utf8'));
		t.put('spyForm/InputSwitch'    , fs.readFileSync(__dirname + '/template/InputSwitch.html'    , 'utf8'));
		t.put('spyForm/InputRadio'     , fs.readFileSync(__dirname + '/template/InputRadio.html'     , 'utf8'));
		t.put('spyForm/InputCheck'     , fs.readFileSync(__dirname + '/template/InputCheck.html'     , 'utf8'));
		t.put('spyForm/InputArea'      , fs.readFileSync(__dirname + '/template/InputArea.html'      , 'utf8'));
		t.put('spyForm/Field'          , fs.readFileSync(__dirname + '/template/Field.html'          , 'utf8'));
		t.put('spyForm/Fieldset'       , fs.readFileSync(__dirname + '/template/Fieldset.html'       , 'utf8'));
		t.put('spyForm/Form'           , fs.readFileSync(__dirname + '/template/Form.html'           , 'utf8'));
	}]);


module.exports.events = require('./event/FormEvent');

require('./service/FormModelService');

require('./controller/FormController');
require('./controller/FieldController');

require('./directive/spyFieldMessage');
require('./directive/spyInputText');
require('./directive/spyInputPassword');
require('./directive/spyInputInteger');
require('./directive/spyInputSelect');
require('./directive/spyInputTextSelect');
require('./directive/spyInputSwitch');
require('./directive/spyInputRadio');
require('./directive/spyInputCheck');
require('./directive/spyInputArea');
require('./directive/spyField');
require('./directive/spyFieldset');
require('./directive/spyForm');

require('./directive/spyFormRead');
require('./directive/spyFormUpdate');
require('./directive/spyFormAbort');
require('./directive/spyFormError');