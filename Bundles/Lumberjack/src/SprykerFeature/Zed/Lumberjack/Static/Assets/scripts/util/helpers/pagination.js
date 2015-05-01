/* global
  define: false
*/
define( function () {
  'use strict';

  return function helper( data ) {
    var pages = [];
    var i = 0;

    while ( ++i <= data.pages ) {
      if ( i === +data.page ) {
        pages.push( '<option disabled selected value="' + i + '">' + i + ' *' );
      } else {
        pages.push( '<option>' + i );
      }
    }

    return pages.join( '' );
  };
});
