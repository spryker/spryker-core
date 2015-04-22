/*global
  define: false,
  console: false
*/
define( [
  'dust'
], function ( dust ) {
  'use strict';

  var components = {};

  return {
    add : function ( name, template ) {
      console.log( '%cadding component %c' + name, 'color:#ccc', 'color:#999' );
      if ( components.hasOwnProperty( name ) ) {
        return this;
      }

      dust.loadSource( dust.compile( template, name, true ) );

      components[ name ] = true;

      console.log( '%ccomponent %c' + name + ' %cadded', 'color:#ccc', 'color:#999', 'color: #bada55'  );

      return this;
    },

    addHelper : function ( name, func ) {
      dust.filters[ name ] = func;

      return this;
    },

    render : function ( name, data, callback ) {
      if ( ! components.hasOwnProperty( name ) ) {
        console.warn( 'component ' + name + ' %cNOT FOUND!', 'color:#c00' );
        return false;
      }

      return dust.render( name, data, callback );
    }
  };
});
