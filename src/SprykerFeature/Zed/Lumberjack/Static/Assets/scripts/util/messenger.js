/*global
  define: false
*/
define([
  'jquery',
  'config',
  'parser'
], function ( $, Config, Parser ) {
  'use strict';

  // console.log( Config );

  function prepareData( model ) {

    // debugger;

    var ESData = {
      query : {
        filtered : {
          // filter : {
          //   range : {}
          // },
          query : {
            'query_string' : {
              query : model.get( 'query' )
            }
          }
        }
      },
      facets : {
        histogram : {
          'date_histogram' : Config.sorting.histogram
        }
      },
      sort : Config.sorting[ 'default' ]
    };

    var range = model.get( 'timespan' );

    if ( range ) {
      var rangeProp = Config.sorting.range || 'dateAndTime';

      // console.log( range );

      ESData.query.filtered.filter = {
        range : {}
      };

      // ESData.query.filtered.filter.range[ rangeProp ] = {
      ESData.query.filtered.filter.range[ rangeProp ] = {
        from : range[ 0 ],
        to   : range[ 1 ]
      };
    }

    var json = JSON.stringify( ESData );

    return Config.proxy ? {
      request : json
    } : json;
  }

  return {

    fetch : function ( model ) {
      var timestamp = Date.now();

      model.set({
        sync : true
      });

      var perPage = model.get( 'resultsPerPage' ) || 10;
      var offset  = perPage * ( model.get( 'page' ) - 1 );

      var extraOptions = {
        size : perPage,
        from : offset
      };

      $.ajax({
        url         : '' + Config.uri.url + Config.uri.search + '?' + $.param( extraOptions ),
        type        : 'POST',
        dataType    : 'json',
        processData : Config.proxy,
        data        : prepareData( model )
      }).success( function ( data ) {

        if ( data.error ) {
          model.set({
            error : data.error
          });
        } else {
          var resultJSON = Parser.JSON( data, Config.keys );

          if ( resultJSON && resultJSON.hits.hits ) {
            resultJSON.hits.hits.offset = offset;
          }

          model.set({
            resultJSON : resultJSON,
            stats      : {
              total       : data.hits.total,
              page        : model.get( 'page' ),
              pages       : 1 + ~~( data.hits.total / perPage ),
              timeTakenES : data.took,
              timeTaken   : Date.now() - timestamp
            }
          });
        }

        // debugger;

        // model.set({
        //   json       : Parser.json( data ),
        //   synced     : true,
        //   time_taken : ( new Date() ).getTime() - model.get( 'timestamp' ),
        //   pages      : ~~ ( data.hits.total / option_size )
        // }).trigger( 'sync-success', data );

      }).fail( function ( xhr, status, error ) {
        // console.log( xhr, status, error );

        // no response content-type available, so just try grab json-structure
        try {
          var serverException = $.parseJSON( xhr.responseText );
          error += '\n';
          error += serverException.message + '\n';
          error += 'in ' + serverException.file + ':' + serverException.line;
        } catch( e ){
          error = 'error: ' + xhr.status;
        }

        model.set({
          error : error
        });

        // model.set({
        //   synced     : true,
        //   time_taken : ( new Date() ).getTime() - model.get( 'timestamp' ),
        //   error      : 'Request failed: HTTP/1.1 ' + xhr.status + ' ' + error
        // });
        // model.trigger( 'sync-fail', data );
      }).always( function () {
        model.set({
          sync : false
        });
      });
    }
  };
});
