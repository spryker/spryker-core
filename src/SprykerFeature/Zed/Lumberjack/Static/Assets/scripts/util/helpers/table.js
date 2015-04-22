/* global
  define: false
*/
define( [ 'config', 'lodash' ], function ( Config, _ ) {
  'use strict';

  function addHead( data ) {
    var html = '';

    if ( _.isObject( data ) ) {
      html += '<thead><tr><th>#';

      Object.keys( data ).forEach( function ( key ) {
        // html += '<th><span>' + key.replace( /^\d+__|.*\./g, '' ) + ' <button class="lumberjack__table_pin-cell pinned"></button></span>';
        html += '<th>' + key.replace( /^\d+__|.*\./g, '' );
      } );
    }

    return html;
  }

  function dumpNode( node ) {
    if ( _.isObject( node ) || _.isArray( node ) ) {
      return '<a class="go-deeper"></a>';
    }

    var html = '<div class="lumberjack__table-cell-content">';

    if ( _.isString( node ) ) {

      if ( /^\d{4}-\d{2}-\d{2}(T(\d\d:\d\d:\d\d)?)?$/.test( node ) ) {
        return  html + '<time>' + node + '</time></div>';
      }

      if ( /^(https?|ftp|file):\/\//i.test( node ) ) {
        return html + '<a href="' + node + '" target="_blank">' + node + '</a><div>';
      }
    }

    return html + '' + node + '</div>';
  }

  function printArray( data ) {
    var html = '<tbody>';

    var offset = data.offset || 0;

    _.forEach( data, function ( row, rowKey ) {
      html += '<tr data-key="' + rowKey + '"><th>' + ( rowKey + offset + 1 );

      _.forEach( row, function ( cell, cellKey ) {
        html += printCell( cell, cellKey.replace( /(^|\.)\d+__/, '$1' ) );
      } );
    } );

    return html;
  }

  function printObject( data ) {
    var html = '<tbody>';

    var groupedRows = [];
    var customRows  = [];
    var defaultRows = [];

    _.forEach( data, function ( row, rowKey ) {
      var html = '<tr>';

      /**
       * TODO
       */

      var isGroupedKey = Config.keys &&
                         Config.keys.grouped &&
                         Config.keys.grouped[ rowKey ];

      if ( isGroupedKey ) {
        var groupedKey = Config.keys.grouped[ rowKey ];

        var executeOnClick = '';

        if ( groupedKey.executeOnClick ) {
          executeOnClick = ' data-execute-on-click="true"';
        }

        html += '<th colspan="2" data-key="' + rowKey + '" data-value="' + row + '"' + executeOnClick + '>';
        html += groupedKey.linkLabel;

        groupedRows.push( html );

        if ( groupedKey.removeFromTable ) {
          return;
        }
      }

      var isNonDefaultKey = Config.keys &&
                            Config.keys.default &&
                            Config.keys.default.indexOf( rowKey ) === -1;

      if ( isNonDefaultKey ) {
        html += '<th class="lumberjack__table-cell_custom-key">' + rowKey;
      } else {
        html += '<th>' + rowKey;
      }

      html += printCell( row, rowKey );

      if ( isNonDefaultKey ) {
        customRows.push( html );
      } else {
        defaultRows.push( html );
      }
    } );

    if ( groupedRows.length ) {
      html = '<thead class="lumberjack__table-row_grouped">' + groupedRows.join( '' ) + html;
    }

    return html + customRows.join( '' ) + defaultRows.join( '' ) ;
  }

  function printCell( cell, key ) {
    var cssClass = 'lumberjack__table-cell_' + ( cell === null ? 'null' : typeof cell );

    return '<td data-key="' + key + '" class="' + cssClass + '">' + dumpNode( cell );
  }

  return function helper( data ) {
    var html = '';

    if ( _.isArray( data ) ) {
      html += addHead( data[ 0 ] );
      html += printArray( data );
    } else {
      html += printObject( data );
    }

    return html;
  };
});
