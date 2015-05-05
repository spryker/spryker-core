'use strict';

var _ng     = require('Ui').ng;
var _event  = require('../event/GridEvent');
var _cevent = require('../event/GridComponentEvent');



/**
 * Basic Grid Controller
 * @ngdoc function
 * @name GridController
 */
_ng
	.module('spyGrid')
	.controller('GridController',[
		'$controller',
		'$scope',
		'$attrs',
		'$window',
		'$location',
		'$q',
		'$timeout',
		'GridModelService',

		function($controller, $scope, $attrs, $window, $location, $q, $timeout, service) {
			$controller('TemplateActionController', {
				$scope : $scope,
				$attrs : $attrs
			});

			var _request  = null;

			var _resource = service($attrs.src);

			var _columns  = [];


			function _scopeToParam(scope) {
				var res = {
					'sort'  : scope.sort.name !== '' ? scope.sort.name : null,
					'dir'   : scope.sort.dir  !== 0  ? (scope.sort.dir > 0 ? 'asc' : 'desc') : null,
					'page'  : scope.pages.page.now,
					'items' : scope.pages.items.now
				};

				for (var id in scope.filters) {
					if (scope.filters.id !== '') res['filter[' + id + ']'] = scope.filters[id];
				}

				return res;
			}

			function _locationToScope(scope, location, prefix) {
				var hash = location.search();
				var expr = new RegExp("^" + prefix + "_"), fexpr = new RegExp("^filter\\[(.+)\\]");

				for (var id in hash) {
					if (id.search(expr) === -1) continue;

					var name  = id.replace(expr, "");
					var match = name.match(fexpr);

					if (match !== null) scope.filters[match[1]]      = hash[id];
					else if (name === 'sort' ) scope.sort.name       = hash[id];
					else if (name === 'dir'  ) scope.sort.dir        = (hash[id] === 'desc' ? -1 : 1);
					else if (name === 'page' ) scope.pages.page.now  = parseInt(hash[id]);
					else if (name === 'items') scope.pages.items.now = parseInt(hash[id]);
				}
			}

			function _paramToLocation(location, prefix, param) {
				var hash = $location.search();
				var name = prefix + '_', len = name.length;

				for (var id in hash) {
					if (id.substr(0, len) === name) delete hash[id];
				}

				for (id in param) {
					if (param[id] !== null && param[id] !== '') hash[prefix + '_' + id] = param[id];
				}

				location.search(hash);
			}


			$scope.columns = [];
			$scope.rows    = [];

			$scope.filters = {};

			$scope.sort = {
				name : '',
				dir  : 0
			};

			$scope.pages = {
				items : {
					min : 1,
					now : 30,
					max : 100,
					num : 0
				},
				page : {
					min : 1,
					now : 1,
					max : 1
				}
			};


			$scope.$watch('columns', function(now, was, scope) {
				if (now === was) return;

				$timeout(function() {
					scope.$broadcast(_cevent.E_ANNOUNCE, now);
				}, 0, false);
			});


			$scope.$on(_cevent.E_FILTER, function(e, data) {
				$scope.filters[data.name] = data.filter;
				$scope.pages.page.now = 1;
			});

			$scope.$watchCollection('filters', function(now, was, scope) {
				if (now === was) return;

				scope.read();
			});


			$scope.$on(_cevent.E_SORT, function(e, data) {
				$scope.sort.name = data.name;
				$scope.sort.dir  = data.dir;
			});

			$scope.$watchCollection('sort', function(now, was, scope) {
				if (now === was) return;

				scope.$broadcast(_cevent.GRID_SORT, {
					name : scope.sort.name,
					dir  : scope.sort.dir
				});

				scope.read();
			});


			$scope.$watch('pages.page.now', function(now, was, scope) {
				if (now === was) return;

				scope.read();
			});

			$scope.$watch('pages.items.now', function(now, was, scope) {
				if (now === was) return;

				scope.pages.page.now = scope.pages.page.min;
				scope.read();
			});


			$scope.read = function() {
				var _param = _scopeToParam($scope);

				_request = _resource.query(_param);

				return _request
					.$promise
					.then(function(model) {
						if (!_ng.equals(_request, model)) return new Error('SPY - request-response mismatch');

						if (!_ng.equals(_columns, model.columns)) {
							_columns = model.columns;

							$scope.columns = _ng.copy(model.columns);
						}

						if ('suggestions' in model) {
							for (var id in $scope.columns) $scope.columns[id] = model.suggestions[id];
						}

						$scope.rows = model.rows;

						$scope.pages.items.num = model.rows.length;
						$scope.pages.page.now = model.page;
						$scope.pages.page.max = model.pages;

						_paramToLocation($location, $scope.name, _param);

						_request = null;

						$scope.$emitAllChannels(_event.E_READ, model);
					}, function(why) {
						console.warn(why);	//jshint ignore:line

						$scope.$emitAllChannels(_event.E_ERROR, why);
					});
			};

			$scope.update = function() {	//DEPRECATED
				return $scope.read();
			};


			_locationToScope($scope, $location, $scope.name);


			$scope
				.read()
				.then(function() {
					$timeout(function() {
						$scope.$broadcast(_cevent.E_SORT, {
							name : $scope.sort.name,
							dir  : $scope.sort.dir
						});

						for (var name in $scope.filters) $scope.$broadcast(_cevent.E_FILTER, {
							name   : name,
							filter : $scope.filters[name]
						});
					});
				});
		}
	]);