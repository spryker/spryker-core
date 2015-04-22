/*global
  define: false
*/
define( function () {
  'use strict';

  return {
    printUTC : function ( date ) {
      var output = '';

      var year  = date.getUTCFullYear();
      var month = date.getUTCMonth() + 1;
      var day   = date.getUTCDate();

      var hour    = date.getUTCHours();
      var minutes = date.getUTCMinutes();
      var seconds = date.getUTCSeconds();

      if ( month <= 9 ) {
        month = '0' + month;
      }

      if ( day <= 9 ) {
        day = '0' + day;
      }

      if ( hour <= 9 ) {
        hour = '0' + hour;
      }

      if ( minutes <= 9 ) {
        minutes = '0' + minutes;
      }

      if ( seconds <= 9 ) {
        seconds = '0' + seconds;
      }

      output += [ year, month, day ].join( '-' );
      output += 'T';
      output += [ hour, minutes, seconds ].join( ':' );

      return output;
    }
  };
} );
