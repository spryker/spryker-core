'use strict';



/**
 * Select input field directive
 * @ngdoc directive
 * @name spyInputSelect
 * @restrict A
 * @param {expression} spy-input-select The field model
 * @param {expression} options          The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputSelect', ['$timeout', function($timeout) {

		function _getSelectableIndex(value, items) {
			for (var i = items.length - 1; i > -1; i -= 1) {
				if (value === items[i].value) return i;
			}

			return i;
		}

		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputSelect',
				options : '=options'
			},

			templateUrl : 'spyForm/InputSelect',

			controller : ['$controller', '$scope', function($controller, $scope) {
				$controller('FieldController', {
					$scope : $scope
				});

				$scope.disabled = false;
				$scope.empty    = "";

				$scope.selected = null;
				$scope.accepted = null;

				$scope.open     = false;

				$scope.$watchGroup(['model', 'options'], function(now, was, scope) {
					if (scope.options === undefined || scope.model === undefined) return;

					var model = scope.model;

					if (scope.options.disabled) scope.disabled = true;
					else if ('empty' in scope.options) scope.disabled = false;
					else if (model.accepts.length < 2) scope.disabled = false;
					else scope.disabled = false;

					scope.accepted = model.accepts.slice(0);

					if ('empty' in scope.options) scope.accepted.unshift({
						value : null,
						label : scope.options.empty
					});

					if (model.value === '') model.value = null;

					scope.selected = null;
					scope.selectable = _getSelectableIndex(scope.model.value, scope.accepted);

					for (var i = scope.accepted.length - 1; i > -1; i -= 1) {
						if (scope.accepted[i].value === model.value) {
							scope.selected = scope.accepted[i];

							break;
						}
					}
				});

				$scope.$watch('model.value', function(now, was, scope) {
					if (now !== null || scope.accepted === null) return;

					if (scope.accepted.length > 0 && !('empty' in scope.options)) scope.selected = scope.accepted[0];
				});

				$scope.$watch('selected', function(now, was, scope) {
					if (now !== null) scope.model.value = now.value;
				});

				$scope.focusSelect = function() {
					$scope.focus = true;
				};

				$scope.blurSelect = function() {
					$scope.focus = $scope.open = false;
				};

				$scope.openList = function() {
					$scope.focus = $scope.open = true;

					$scope.selectable = _getSelectableIndex($scope.model.value, $scope.accepted);
				};

				$scope.closeList = function() {
					$scope.open = false;

					$scope.selected = $scope.accepted[$scope.selectable > -1 ? $scope.selectable : 0];
				};

				$scope.selectItem = function(item) {
					$scope.selected = item;
				};

			}],

			link : function(scope, selector, attributes) {
				var _element = selector[0];
				var _label   = _element.querySelector('label');
				var _input   = _element.querySelector('div.select');
				var _list    = _input.querySelector('ul');

				_element.classList.add('field', 'select');

				function _onLabelClick(e) {
					_input.focus();
				}

				function _onKeyDown(e) {
					switch (e.keyCode) {
						case 27 :	//ESC
							if (scope.open) {
								scope.selectable = _getSelectableIndex(scope.model.value, scope.accepted);
								scope.closeList();
							}

							break;

						case 32 :	//SPACE
							if (scope.open) scope.closeList();
							else scope.openList();

							break;

						case 38 :	//UP
							scope.selectable = Math.max(scope.selectable - 1, 0);

							if (!scope.open) scope.selectItem(scope.accepted[scope.selectable]);

							break;

						case 40 :	//DOWN
							scope.selectable = Math.min(scope.selectable + 1, scope.accepted.length - 1);

							if (!scope.open) scope.selectItem(scope.accepted[scope.selectable]);

							break;

						default : return;

					}

					scope.$apply();

					e.preventDefault();
				}


				scope.$watch('open', function(now, was, scope) {
					if (now) {
						_list.style.height = 'auto';

						var _h = _list.clientHeight;

						_list.style.height = '0';

						$timeout(function() {
							_list.style.height = _h.toString() + 'px';
						}, 0);
					}
					else _list.style.height = null;
				});


				scope.setSelectable = function(index) {
					scope.selectable = index;
				};


				_label.addEventListener('click'  , _onLabelClick, false);
				_input.addEventListener('keydown', _onKeyDown   , false);

				scope.$on('$destroy', function() {
					_label.removeEventListener('click'  , _onLabelClick, false);
					_input.removeEventListener('keydown', _onKeyDown   , false);
				});
			}
		};
	}]);