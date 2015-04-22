/*global
  define: false
*/
define([
  'lodash'
], function( _ ){
  'use strict';

  function elevate( obj ) {
    var regex          = /\.[^\s]+/;
    var regexInner     = /^.*?\./;
    var regexBlacklist =  /[^\w+\-*]/g;
    var temp;

    _.each( obj, function ( val, key ) {
      var ref = obj;
      var dug = false;
      var originalKey = key;

      while( regex.test( key ) ) {

        dug  = true;
        temp = key.match( regexInner )[ 0 ].replace( regexBlacklist, '' );
        key  = key.replace( regexInner, '' );
        // console.log( 'temp ', temp, '\nkey', key );

        if ( ! ( temp in ref )) {
          ref[ temp ] = {};
        }
        ref = ref[ temp ];

        // now check if it still makes sense to iterate
        // if not - put in the value
        if ( ! regex.test( key )) {
          ref[ key.replace( regexBlacklist, '' )] = val;
        }
      }

      // delete the old flat key
      if ( dug ) {
        delete obj[ originalKey ];
      }
    });
    return obj;
  }

  function filterOut( obj, config ) {
    if ( ! config.hidden ){
      return obj;
    }

    _.each( config.hidden, function ( key ) {
      delete obj[ key ];
    });

    return obj;
  }

  function sortProps( obj ){
    if ( !obj || ! ( obj instanceof Object ) ) {
      return obj;
    }

    var sortedObj = {};

    _.each( Object.keys( obj ).sort(), function ( prop ) {
      sortedObj[ prop ] = sortProps( obj[ prop ] );
    });

    return sortedObj;
  }

  return function( data, config ){
    if ( !data.hits.total || !config || !config.visibleInOverview ) {
      return data;
    }

    _.each( data.hits.hits, function ( hit, index ) {
      var modified = {};

      _.each( config.visibleInOverview, function ( key, index ) {
        modified[ index + '__' + key ] = hit._source[ key ] || null;
      });

      modified[ '{source}' ] = filterOut( elevate( hit._source ), config );

      data.hits.hits[ index ] = sortProps( modified );
    });

    return data;
  };
});
