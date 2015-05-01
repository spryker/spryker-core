/*global
  require: false
*/
require.config({
  urlArgs : 'nocache=' + ( new Date() ).getTime(),

  shim : {
    lodash : {
      exports : '_'
    },

    d3 : {
      exports : 'd3'
    },

    parser : {
      exports : 'Parser'
    },

    backbone : {
      exports : 'Backbone',
      deps    : [
        'lodash',
        'jquery'
      ]
    },

    dust : {
      exports : 'dust'
    }
  },

  paths : {
    lodash     : 'vendor/lodash.min',
    backbone   : 'vendor/backbone-min',
    jquery     : 'vendor/jquery.min',

    text       : 'vendor/text',
    dust       : 'vendor/dust-full-2.0.0.min',

    component  : 'util/component',
    parser     : 'util/parser',
    elastic    : 'util/elastic',
    messenger  : 'util/messenger',
    chronos    : 'util/chronos',

    d3         : 'vendor/d3.v3.min',
    charts     : 'util/charts',

    config     : 'util/config'
  }
});
