require('Ui').ng
	.module('spyBase')
	.factory('ErrorStoreService', [function() {
		var _errors = [];

		return {
			push : function(error) {
				_errors.push(error);
			},

			pop : function() {
				return _errors.pop();
			},

			has : function() {
				return _errors.length !== 0;
			},

			get : function() {
				return _errors[_errors.length - 1];
			},

			intercept : function(model, headers) {
				_errors.push(model);

				return model;
			}
		}
	}]);