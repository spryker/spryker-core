'use strict';



/**
 * Typeable select input field directive
 * @ngdoc directive
 * @name spyInputTextSelect
 * @restrict A
 * @param {expression} spy-input-text-select The field model
 * @param {expression} options               The field options
 */
require('Ui').ng
	.module('spyForm')
	.directive('spyInputTextSelect', ['$timeout', '$window', function($timeout, $window) {
		return {
			restrict : 'A',

			scope : {
				model   : '=spyInputTextSelect',
				options : '=options'
			},

			templateUrl : 'spyForm/InputTextSelect',

			controller : ['$controller', '$scope', '$timeout', function($controller, $scope, $timeout) {
				$controller('FieldController', {
					$scope : $scope
				});

				$scope.value     = "";
				$scope.selection = [];

				$scope.selectable = -1;


				$scope.$watch('value', function(now, was, scope) {
					if (now === was) return;

					if (now === "") {
						scope.selection = [];

						if (scope.model !== undefined) scope.model.value = null;

						return;
					}

					var select = scope.model.accepts
						.filter(function(item, index, source) {
							return item.label.match(now) !== null;
						});

					if (
						select.length === 1 || 
						select.some(function(item, index, source) {
							return item.label === now;
						})
					) {
						scope.model.value = select[0].value;
						scope.value = select[0].label;

						select = [];
					}
					else scope.model.value = null;

					scope.selection = select;
					scope.selectable = -1;
				});

				$scope.$watch('model.value', function(now, was, scope) {
					if (now === was) return;

					for (var i = scope.model.accepts.length - 1; i > -1; --i) {
						if (scope.model.accepts[i].value === now) {
							scope.value = scope.model.accepts[i].label;
						}
					}
				});


				$scope.selectItem = function(item) {
					$scope.value = item.label;
				};

				$scope.focusInput = function() {
					$scope.focus = true;
				};

				$scope.blurInput = function() {
					$scope.focus = false;
				};
			}],

			link : function(scope, selector, attributes) {
				var _element = selector[0];
				var _group   = _element.querySelector('div.select');
				var _input   = _group.querySelector('input');
				var _list    = _group.querySelector('ul');

				_element.classList.add('field', 'textselect');


				function _onKeyDown(e) {
					var item, y, h;

					switch(e.keyCode) {
						case 32 :	//SPACE
							if (scope.selectable === -1) return;

							scope.selectItem(scope.selection[scope.selectable]);

							break;

						case 38 :	//UP
							scope.selectable = Math.max(scope.selectable - 1, 0);

							item = _list.children[scope.selectable];
							h = item.clientHeight, y = h * scope.selectable;

							if (y < _list.scrollTop) _list.scrollTop = y;

							break;

						case 40 :	//DOWN
							scope.selectable = Math.min(scope.selectable + 1, scope.selection.length -1);

							item = _list.children[scope.selectable];
							h = item.clientHeight, y = h * scope.selectable;

							if (y + h > _list.scrollTop + _list.clientHeight) _list.scrollTop = y + h - _list.clientHeight;

							break;

						default : return;
					}

					scope.$apply();

					e.preventDefault();
				}


				scope.$watch('focus', function(now, was, scope) {
					var h = 0;

					if (now) h = parseInt($window.getComputedStyle(_list).lineHeight);

					_list.style.height = (scope.selection.length * h) + "px";
				});

				scope.$watchCollection('selection', function(now, was, scope) {
					var h = parseInt($window.getComputedStyle(_list).lineHeight);

					_list.style.height = (now.length * h) + "px";
				});


				scope.setSelectable = function(index) {
					scope.selectable = index;
				};


				_input.addEventListener('keydown', _onKeyDown, false);

				scope.$on('$destroy', function() {
					_input.removeEventListener('keydown', _onKeyDown, false);
				});
			}
		};
	}]);