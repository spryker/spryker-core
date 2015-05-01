/* global
  define: false
*/
define([
  './parser/json_parser'
], function( JSON ){
  'use strict';

  var parser = {
    JSON : function( x, config ){
      return JSON( x, config );
    }
  };

  return parser;
});
