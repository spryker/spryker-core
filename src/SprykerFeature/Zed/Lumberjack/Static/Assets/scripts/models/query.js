/*global
  define: false,
  console: false
*/
define([
  'lodash',
  'backbone',
  'messenger'
], function ( _, Backbone, Messenger ) {
  'use strict';

  var propMapper = {
    'c'     : 'command',
    'int'   : 'reloadInterval',
    'qty'   : 'resultsPerPage',
    'rld'   : 'autoReload',
    'start' : 'timespanStart',
    'end'   : 'timespanEnd'
  };

  return Backbone.Model.extend({
    defaults : {
      hash    : '',
      query   : '',
      command : 'search',
      error   : null,
      sync    : true,

      page       : 1,
      pages      : 1,
      stats      : null,
      resultJSON : null,

      autoReload     : false,
      reloadInterval : 0,

      timespan       : null
    },

    initialize : function ( opts ) {
      var props = {};

      if ( opts.hash ) {
        var queryRegex = /q:\{(.+)\}/;
        var query = opts.hash.match( queryRegex );

        if ( query ) {
          props.query = decodeURI( query[ 1 ] );
        }

        var propRegex = /(\w+):(.+)/;
        var match;

        _.each( opts.hash.replace( queryRegex, '' ).split( '|' ), function ( val ) {
          match = val.match( propRegex );

          if ( ! match || ! ( match[ 1 ] in propMapper ) ) {
            return;
          }

          props[ propMapper[ match[ 1 ] ] ] = match[ 2 ];
        });
      }

      if ( props.timespanStart || props.timespanEnd ) {
        props.timespan = [ props.timespanStart || null ];
        if ( props.timespanEnd ) {
          props.timespan.push( props.timespanEnd );
        }

        delete props.timespanStart;
        delete props.timespanEnd;
      }

      console.log( props );

      this.set( props );
      this.fetch();
    },

    data : function () {
      return '';
    },

    sync : function( method, model, options ) {

      console.log( method, options );

      if ( method === 'read' ) {
        Messenger.fetch( model );
      }
    }
  });
});
