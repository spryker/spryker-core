'use strict';

var _ng = require('Ui').ng;

var _scope = {};

/**
 * Basic Communication Controller
 * @ngdoc function
 * @name ComController
 */
_ng
	.module('spyBase')
	.controller('ComController', [
		'$scope',
		'$attrs',

		function($scope, $attrs) {
			$scope.name    = 'name'    in $attrs ? $attrs.name : '';
			$scope.channel = 'channel' in $attrs ? $attrs.channel.split(',') : [];


			$scope.$emitAsChannel = function(type, channel, obj) {
				$scope.$emit(type, obj, channel);
			};

			$scope.$emitAllChannels = function(type, obj) {
				$scope.channel.forEach(function(item, index, source) {
					$scope.$emit(type, obj, item);
				});
			};


			if (!('context' in $scope)) $scope.context = {};

			if (!('getScopeByName' in $scope)) $scope.getScopeByName = function(name) {
				return name in _scope ? _scope[name] : null;
			};


			$attrs.$observe('name', function(now) {
				$scope.name = now !== undefined ? now : '';
			});

			$attrs.$observe('channel', function(now) {
				$scope.channel = now !== undefined ? now.split(',') : [];
			});


			$scope.$watch('name', function(now, was, scope) {
				if (was !== '' && now !== was) delete _scope[was];
				if (now !== '') {
					if (now in _scope) throw new Error("SPY - Duplicate use of scope name: " + now);

					_scope[now] = scope;
				}
			});


			$scope.$on('$destroy', function(e) {
				if ($scope.name !== '') delete _scope[$scope.name];
			});
		}
	]);