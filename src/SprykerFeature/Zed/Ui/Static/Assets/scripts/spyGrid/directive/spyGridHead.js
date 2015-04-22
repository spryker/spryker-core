'use strict';

var _ng    = require('Ui').ng;
var _event = require('../event/GridComponentEvent');



var _id = 0;

/**
 * Grid column head with sorting & filtering
 * @ngdoc directive
 * @name spyGridHead
 * @restrict A
 * @param {expression} spy-grid-head The column model
 * @param {string}     label         The column label
 */
_ng
	.module('spyGrid')
	.directive('spyGridHead', ['$timeout', function($timeout) {

		return {
			restrict : 'A',

			scope : {
				'column' : '=spyGridHead',
				'label'  : '@label'
			},

			templateUrl : 'spyGrid/GridHead',

			controller : ['$scope', function($scope) {
				$scope.filterEnabled = false;
				$scope.filter  = '';

				$scope.sortDir = 0;

				$scope.name = 'grid' + (++_id).toString();


				$scope.$on(_event.E_ANNOUNCE, function(e, data) {
					if ($scope.column === undefined)
						console.warn('SPY - Gridhead not connected to model');	//jshint ignore:line
				});


				$scope.$on(_event.E_FILTER, function(e, data) {
					if ($scope.column === undefined || data.name !== $scope.column.name) return;

					$scope.filter = data.filter;
				});

				$scope.$watch('filter', function(now, was, scope) {
					if (now === was) return;

					$scope.$emit(_event.E_FILTER, {
						'name'   : scope.column.name,
						'filter' : scope.filter
					});
				});


				$scope.$on(_event.E_SORT, function(e, data) {
					if ($scope.column === undefined || data.name === $scope.column.name) $scope.sortDir = data.dir;
					else $scope.sortDir = 0;
				});

				$scope.$watch('sortDir', function(now, was, scope) {
					if (now === was || now === 0) return;

					$scope.$emit(_event.E_SORT, {
						'name'   : scope.column.name,
						'dir'    : scope.sortDir
					});
				});


				$scope.clearFilter = function() {
					$scope.filter = "";
				};

				$scope.sort = function() {
					$scope.sortDir = $scope.sortDir !== 0 ? $scope.sortDir * -1 : 1;
				};
			}],

			link : function(scope, selector, attributes) {
				var _element = selector[0];
				var _title   = _element.querySelector('div.title');
				var _icon    = _element.querySelector('span');
				var _label   = _element.querySelector('label');
				var _input   = _element.querySelector('input');

				var _w = 0;


				function _onClick(e) {
					if (scope.filterEnabled) {
						scope.filterEnabled = false;
						scope.filter = "";

						scope.$apply();
					}
					else _input.focus();
				}

				function _onFocus(e) {
					scope.filterEnabled = true;
					scope.$apply();
				}

				function _onBlur(e) {
					$timeout(function() {
						scope.filterEnabled = scope.filter !== "";
						scope.$apply();
					}, 200);
				}


				scope.$watch('column', function(now, was, scope) {
					if (now === undefined) return;

					if (now.filterable) _element.classList.add('filter');
					else _element.classList.remove('filter');

					if (now.sortable) _element.classList.add('sort');
					else _element.classList.remove('sort');
				});


				scope.$watch('label', function(now, was, scope) {
					if (now === undefined) _w = 0;
					else _w = _label.clientWidth;

					if (!scope.filterEnabled) _title.style.width = _w + "px";
				});

				scope.$watch('filterEnabled', function(now, was, scope) {
					if (now) _title.style.width = null;
					else _title.style.width = _w + "px";
				});


				_icon.addEventListener('click' , _onClick, false);
				_input.addEventListener('focus', _onFocus, false);
				_input.addEventListener('blur' , _onBlur , false);

				scope.$on('$destroy', function(e) {
					_icon.removeEventListener('click' , _onClick, false);
					_input.removeEventListener('focus', _onFocus, false);
					_input.removeEventListener('blur' , _onBlur , false);
				});
			}
		};
	}]);