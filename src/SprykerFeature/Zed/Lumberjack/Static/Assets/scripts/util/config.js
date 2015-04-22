/*global
  define: false,
  console: false
*/
define([
  'jquery'
], function ( $ ) {
  'use strict';

  var $script = $( 'script[data-lumberjack-config]' );

  if ( ! $script.length ){
    return false;
  }

  var cfg = $script.first().data( 'lumberjack-config' );

  if ( !( cfg instanceof Object )){
    try {
      cfg = JSON.parse( cfg );
    } catch ( err ) {
      console.error( 'lumberJACK: config corrupt', err );
      return false;
    }
  }

  if ( !cfg ) {
    return false;
  }

  return cfg;

  // return {
  //   'container'    : '#lumberjack',
  //   'sticky_top'   : 0,
  //   'default_size' : 25,
  //   'uri' : {
  //     'url'     : '/lumberjack/elastic-search-proxy/',
  //     'mapping' : 'mapping',
  //     'search'  : 'search'
  //   },
  //   'sorting' : {
  //     'default' : {
  //       'microtime' : 'desc'
  //     },
  //     'histogram' : {
  //       'field'    : 'microtime',
  //       'interval' : 'second',
  //       'factor'   : 10
  //     },
  //     'range' : 'dateAndTime'
  //   },
  //   'keys' : {
  //     'default' : [
  //       'dateAndTime',
  //       'environment',
  //       'host',
  //       'ip',
  //       'language',
  //       'locale',
  //       'message',
  //       'requestIdZed',
  //       'requestIdYved',
  //       'route',
  //       'store',
  //       'subtype',
  //       'type',
  //       'url',
  //       'application'
  //     ],
  //     'hidden' : [
  //       'microtime',
  //       'dateAndTime'
  //     ],
  //     'visibleInOverview' : [
  //       'dateAndTime',
  //       'message',
  //       'application',
  //       'route',
  //       'type',
  //       'subtype'
  //     ],
  //     'grouped' : {
  //       'requestIdZed' : {
  //         'executeOnClick'  : true,
  //         'linkLabel'       : 'Show all logs that happend in this ZED Request',
  //         'removeFromTable' : true
  //       },
  //       'requestIdYves' : {
  //         'executeOnClick'  : true,
  //         'linkLabel'       : 'Show all logs that happend in this Yves Request',
  //         'removeFromTable' : true
  //       }
  //     }
  //   },
  //   'proxy' : true
  // };
});
