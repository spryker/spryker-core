/*global
  define:false
*/
define( [ 'lodash' ], function ( _ ) {
  'use strict';

  var nastyPlaceholder = '☠';

  var expressions = {
    // http://stackoverflow.com/questions/5695240/php-regex-to-ignore-escaped-quotes-within-quotes/5696141#5696141
    quotedValues : /"[^\\]*(?:\\.[^"\\]*)*"/g,
    key          : /^([A-Z0-9_.-]+):/i
  };

  function Node( value, key ) {
    this.key   = key;
    this.value = value;
  }

  function escapeQuotes( string ) {
    if ( /\s|"/.test( string ) ) {
      return '"' + string.replace( /^"|"$/g, '' ).replace( /"/g, '\"' ) + '"';
    }

    return string;
  }

  Node.prototype = Object.create( {
    toString : function () {
      var printValue = escapeQuotes( this.value );

      if ( this.key ) {
        return this.key + ':' + printValue;
      }

      return printValue;
    },

    toHTML : function () {
      var html = '';

      if ( this.key ) {
        html += '<var class="key">' + this.key + '</var>:';
      }

      html += '<var class="value">' + escapeQuotes( this.value ).replace( /"/g, '&quot;' ) + '</var>';

      return html;
    },

    get : function () {
      var obj = {
        value : this.value
      };

      if ( this.key ) {
        obj.key = this.key;
      }

      return obj;
    }
  } );

  function Operator( operator ) {
    this.operator = operator || 'OR';
  }

  Operator.prototype = Object.create( {
    toString : function () {
      return this.operator;
    },

    toHTML : function () {
      return '<var class="operator">' + this.operator + '</var>';
    }
  } );

  function stringToNode( string ) {
    if ( /^(AND|OR)$/.test( string ) ) {
      return new Operator( string );
    }

    if ( string === undefined || string === null || ! string.toString ) {
      return new Node( '' );
    }

    string = string.toString();

    var matches = string.match( expressions.key );

    if ( matches ) {
      return new Node( string.replace( expressions.key, '' ), matches[ 1 ] );
    }

    return new Node( string );
  }

  function fillWithOperators( array ) {
    var indicesForOperators = [];

    array.forEach( function ( node, index ) {
      if ( !index ) {
        return node;
      }

      if ( array[ index - 1 ] instanceof Node && node instanceof Node ) {
        indicesForOperators.push( index );
      }
    });

    indicesForOperators.reverse().forEach( function ( index ) {
      array.splice( index, 0, new Operator() );
    } );

    return array;
  }

  function Query( queryString ) {
    this.query = [];

    var isInvalid = this.isInvalid( queryString );

    if ( isInvalid ) {
      // query is invalid
      throw new Error( isInvalid );
    }

    if ( queryString || queryString === false || queryString === 0 ) {
      this.query = this.parse( queryString );
    }

    return this;
  }

  Query.prototype = Object.create( {
    isInvalid : function ( queryString ) {
      if ( ! queryString ) {
        return false;
      }

      var strippedQuery = queryString.toString().replace( expressions.quotedValues, nastyPlaceholder );

      // stripped query should not contain any quotes
      if ( strippedQuery.match( /"/g ) ) {
        return this.QUOTE_MISSMATCH;
      }

      // stripped query should not contain any keys without values
      if ( strippedQuery.match( /(^|\s)\w+:(\s|$)/ ) ) {
        return this.VALUELESS_KEY;
      }

      if ( strippedQuery.match ( /(^|\s)[^\w☠*](\s|$)/ ) ) {
        return this.UNQUOTED_VALUE;
      }

      return false;
    },

    QUOTE_MISSMATCH : 'quotes missmatch',
    VALUELESS_KEY   : 'valueless key',
    UNQUOTED_VALUE  : 'unquoted value',

    parse : function ( queryString ) {

      if ( ! /\s/.test( queryString ) ) {
        return [ stringToNode( queryString ) ];
      }

      // look for lack of any quotes
      if ( ! /"/.test( queryString ) ) {
        return fillWithOperators( queryString.split( ' ' ).map( function ( fraction ) {
          return stringToNode( fraction );
        } ) );
      }

      var quotedValues = [];

      return fillWithOperators( queryString.replace( expressions.quotedValues, function ( match ) {
        quotedValues.push( match );

        return nastyPlaceholder;
      } ).split( ' ' ).map( function ( fraction ) {

        return stringToNode( fraction.replace( nastyPlaceholder, function () {
          return quotedValues.shift();
        } ) );
      } ) );
    },

    set : function ( key, value, and ) {
      if ( ! this.query || ! this.query.length ) {
        this.query = [ new Node ( value, key ) ];
        return this;
      }

      var index = _.findIndex( this.query, function ( node ) {
        return node.key === key;
      } );

      if ( ! ~ index ) {
        this.query.push( new Operator( and ? 'AND' : 'OR' ), new Node ( value, key ) );
      } else {
        this.query[ index ].value = value;
      }

      return this;
    },

    toString : function () {
      return this.query.join( ' ' );
    },

    toHTML : function () {
      return this.query.map( function ( node ) {
        return node.toHTML();
      } ).join( ' ' );
    }
  } );

  return Query;
});
