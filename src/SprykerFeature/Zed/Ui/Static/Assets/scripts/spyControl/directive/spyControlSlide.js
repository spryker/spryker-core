'use strict';

/**
 * Complex numerical input control directive
 * @ngdoc directive
 * @name spyControlSlide
 * @restrict A
 * @param {expression} spy-control-slide The control model
 */
require('Ui').ng
	.module('spyControl')
	.directive('spyControlSlide', ['$document', function($document) {
		return {
			restrict : 'A',

			scope : {
				data : '=spyControlSlide'
			},

			templateUrl : 'spyControl/ControlSlide',

			link : function(scope, selector, attributes) {
				var _body  = $document[0].querySelector("body");
				var _input = selector[0].querySelector("input");

				var _snum = 0, _dnum = 0.0;

				var _sx = 0, _sy = 0, _dlen = 0;
				var _active = 0x0, _ivid = 0, _time = 0;


				function _tick(time) {
					var dt = (time - _time);

					_time = time;
					_dnum += _dlen / dt;

					scope.now = Math.min(Math.max(Math.round(_snum + _dnum), scope.data.min), scope.data.max);
					scope.$apply();

					_ivid = requestAnimationFrame(_tick);
				}


				function _onMouseDown(e) {
					_sx = e.pageX, _sy = e.pageY;

					_body.addEventListener('mousemove', _onMouseMove, false);
					_body.addEventListener('mouseup'  , _onMouseUp  , false);

					_snum = scope.data.now;
					_dnum = 0;

					_active |= 0x1;
				}

				function _onMouseUp(e) {
					_body.removeEventListener('mousemove' , _onMouseMove, false);
					_body.removeEventListener('mouseup'   , _onMouseUp  , false);

					scope.data.now = scope.now;
					scope.$apply();

					_active &= ~0x1;

					if (_active === 0x0) {
						cancelAnimationFrame(_ivid);
						_ivid = 0;
					}
				}

				function _onMouseMove(e) {
					var dx  = e.pageX - _sx, dy = _sy - e.pageY;
					var dxy = Math.sqrt(dx * dx + dy * dy);
					var maj = Math.abs(dx) > Math.abs(dy) ? dx : dy;
					var sgn = (maj > 0) - (0 > maj);

					_dlen = sgn * dxy * 0.1;

					if (_ivid === 0) {
						_time = performance.now();
						_ivid = requestAnimationFrame(_tick);

						_input.blur();
					}
				}

				function _onFocus(e) {
					_input.select();
				}


				function _onBlur(e) {
					if (_active & 0x6) {
						_active = _active & ~0x6;

						_input.removeEventListener('keyup', _onKeyUp, false);
						_input.removeEventListener('blur' , _onBlur , false);
					}
				}


				function _onKeyDown(e) {
					var active = _active;

					switch (e.keyCode) {
						case 38 : //UP
							_active |= 0x2;
							break;

						case 40 : //DOWN
							_active |= 0x4;
							break;

						default : return;
					}

					var incr = _active & 0x4 ? -1 : 1;

					if (e.ctrlKey || e.metaKey) incr *= scope.data.max;
					else if (e.shiftKey) incr *= 10;

					scope.now = Math.min(Math.max(scope.now + incr, scope.data.min), scope.data.max);
					scope.$apply();

					if (~active & 0x6) {
						_input.addEventListener('keyup', _onKeyUp, false);
						_input.addEventListener('blur' , _onBlur , false);
					}

					e.preventDefault();
				}

				function _onKeyUp(e) {
					switch (e.keyCode) {
						case 38 : //UP
							_active &= ~0x2;
							break;

						case 40 : //DOWN
							_active &= ~0x4;
							break;

						default : return;
					}

					if (_active !== 0x0) return;

					scope.data.now = scope.now;
					scope.$apply();

					cancelAnimationFrame(_ivid);
					_ivid = 0;

					_input.removeEventListener('keyup', _onKeyUp, false);
					_input.removeEventListener('blur' , _onBlur , false);
				}


				_input.addEventListener('mousedown', _onMouseDown, false);
				_input.addEventListener('focus'    , _onFocus    , false);
				_input.addEventListener('keydown'  , _onKeyDown  , false);


				scope.now = 0;


				scope.$watch('data.now', function(now, was, scope) {
					scope.now = scope.data.now;
				});

				scope.$watch('now', function(now, was, scope) {
					if (now === was) return;

					if (!_active) {
						if (typeof now === 'string') {
							now = Math.round(parseFloat(now));
							scope.now = now;
						}

						scope.data.now = now;
					}
				});


				scope.$on('control.pull.focus', function(e) {
					_input.focus();
				});


				scope.$on('$destroy', function(e) {
					_input.removeEventListener('mousedown', _onMouseDown, false);
					_input.removeEventListener('focus'    , _onFocus    , false);
					_input.removeEventListener('keydown'  , _onKeyDown  , false);
				});
			}
		};
	}]);

