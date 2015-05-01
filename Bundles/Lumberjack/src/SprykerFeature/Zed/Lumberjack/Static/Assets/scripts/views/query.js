/* global
  define: false,
  console: fasle
*/
define([
  'jquery',
  'lodash',
  'backbone',

  'charts',

  'component',
  'text!../../templates/query.dust',
  'text!../../templates/result.dust',
  'text!../../templates/result-table.dust',

  'util/helpers/table',
  'util/helpers/pagination'
], function ( $, _, Backbone, Charts, Component, queryTemplate, resultTemplate,
    resultTableTemplate, tableHelper, paginationHelper ) {
  'use strict';

  function getKeyChain( $cell ) {
    var $parents = $cell.parentsUntil( '.lumberjack__table' );

    return $parents
      .add( $parents.last().parent() )
      .filter( '[data-key]' )
        .map( function () {
          return $( this ).data( 'key' );
        })
        .get()
          .reverse()
          .concat( [ $cell.data( 'key' ) ] )
            .join( '.' );
  }

  function extractValue( json, selector ) {
    if ( selector ) {
      _.each( selector.split( '.' ), function ( key ) {
        if ( key in json ) {
          json = json[ key ];
        }
      } );
    }

    return json;
  }

  function patchHistogram( dataset, extent ) {
    dataset = dataset.map( function ( data ) {
      return {
        date  : new Date( data.time / 100 ),
        value : +data.count
      };
    } );

    if ( ! extent ) {
      return dataset;
    }

    var from = new Date( extent[ 0 ] );
    var to   = new Date( extent[ 1 ] );

    if ( from && dataset[ 0 ].date > from ) {
      dataset.unshift( {
        date  : from,
        value : 0
      } );
    }

    if ( to && dataset[ dataset.length - 1 ].date < to ) {
      dataset.push( {
        date  : new Date( to ),
        value : 0
      } );
    }

    return dataset;
  }

  return Backbone.View.extend( {
    tagName   : 'li',
    className : 'lumberjack__query-result status:sync',

    initialize : function () {
      Component
        .add( 'lumberjack--query', queryTemplate )
        .add( 'lumberjack--result', resultTemplate )
        .add( 'lumberjack--result-table', resultTableTemplate )
        .addHelper( 'LJtable', tableHelper )
        .addHelper( 'LJpagination', paginationHelper );

      this.model.on({
        'change:resultJSON' : this.render,
        'change:error'      : this.render,
        'change:sync'       : this.toggleSync
      }, this );
    },

    events : {
      'click .go-deeper'         : 'showTable',
      'click .lumberjack__query' : 'toggleResult',
      'brushend svg'             : 'timespanBrush',

      'submit .lumberjack__query-pagination' : 'navigateToPage',
      'change .lumberjack__query-pagination' : 'showPageSubmit',

      'click .lumberjack__table-cell-content'   : 'useCellContent',
      'click .lumberjack__table-row_grouped th' : 'useGroup',
      // 'click .lumberjack__table_pin-cell'     : 'pinCell',

      'click .lumberjack__modal-window__close-button' : 'hideModal'
    },

    showPageSubmit : function ( ev ) {
      // console.log( ev );

      $( ev.currentTarget ).addClass( 'is-changed' );
    },

    navigateToPage : function ( ev ) {
      ev.preventDefault();
      ev.stopPropagation();

      this.model
        .set( $( ev.target ).formObject() )
        .fetch();
    },

    render : function () {
      var view = this;
      var model = this.model;
      var json  = model.get( 'resultJSON' );

      if ( view.$el.empty() ) {
        Component.render( 'lumberjack--query', model.attributes, function ( err, html ) {
          view.$el.html( html );
        } );
      }

      Component.render( 'lumberjack--result', model.attributes, function ( err, html ) {
        view.$el.find( '.lumberjack__result' ).html( html );
      } );

      if ( json && json.facets.histogram && json.facets.histogram.entries.length ) {
        var $chartContainer = view.$el.find( '.lumberjack__result-chart' );
        var svg             =  $chartContainer.find( 'svg' ).get( 0 );

        Charts.histogram( svg, patchHistogram( json.facets.histogram.entries, model.get( 'timespan' ) ) , {
          height   : $chartContainer.height(),
          width    : $chartContainer.width(),
          timespan : model.get( 'timespan' )
        } );
      }

      this.toggleError();

      return this;
    },

    toggleResult : function ( ev ) {
      if ( ev.target.href ) {
        return ev;
      }

      this.$el.toggleClass( 'active' );
    },

    timespanBrush : function ( ev ) {
      ev.stopPropagation();

      this.model.set( {
        timespan : ev.originalEvent.detail.extent
      } );
    },

    adjacentTable : function ( ev, $target, $table ) {
      $table.nextAll().remove();

      if ( $target.hasClass( 'active' )) {
        $target.removeClass( 'active' );

        return this;
      }

      this.expandTable( $target, function ( err, html ) {
        var $newTable = $( html );

        $table.after( $newTable );

        if ( ev.shiftKey ) {
          $newTable.find( '.go-deeper' ).click();
        }
      } );

      $table.find( '.go-deeper.active' ).removeClass( 'active' );
      $target.addClass( 'active' );
    },

    nestTable : function ( ev, $target ) {
      if ( $target.hasClass( 'active' )) {
        $target.nextAll().remove();
        $target.removeClass( 'active' );

        return this;
      }

      this.expandTable( $target, function ( err, html ) {
        $target.after( html );

        if ( ev.shiftKey || ! ev.originalEvent ) {
          $target.parent().find( '.go-deeper' ).not( $target ).click();
        }
      } );

      $target.addClass( 'active' );
    },

    expandTable : function ( $target, renderCallback ) {
      var $keyHolder = $target.closest( '[data-key]' );
      var keyChain   = getKeyChain( $keyHolder );
      var json       = extractValue( this.model.get( 'resultJSON' ).hits.hits, keyChain );

      Component.render( 'lumberjack--result-table', {
          json : json,
          key  : keyChain
        }, renderCallback );
    },

    showTable : function ( ev ) {
      ev.preventDefault();

      var $target = $( ev.target );
      console.log( $target );

      var $table  = $target.closest( '.lumberjack__table' );

      if ( $table.data( 'key' ) ) {
        // table within table-cell
        this.nestTable( ev, $target, $table );
      } else {
        // table next to the current one
        this.adjacentTable( ev, $target, $table );
      }

      return this;
    },

    renderContent : function () {
      // console.log( this.model.get( 'resultJSON' ) );
      return this;
    },

    toggleSync : function () {
      this.$el.toggleClass( 'status:sync', this.model.get( 'sync' ) );

      return this;
    },

    toggleError : function () {
      this.$el.toggleClass( 'status:error', !! this.model.get( 'error' ) );

      return this;
    },

    useCellContent : function ( ev ) {
      var $target = $( ev.target );

      var $keyHolder = $target.parent();

      var key = getKeyChain( $keyHolder ).replace( /(^[\d]+\.|\{source\}\.|_source\.)/g, '' );
      var val = $target.text();

      if ( ev.offsetX === undefined ) {
        var offset = $target.offset();

        ev.offsetX = ev.pageX - offset.left;
        ev.offsetY = ev.pageY - offset.top;
      }

      if ( ev.offsetY <= 20 && ev.offsetX >= $target.outerWidth() - 20 ) {
        // $target.toggleClass( 'is-expanded' );
        this.showModal( $target, key, val );
        return ev;
      }

      // if ( /\s|"|\//.test( val ) ) {
      //   val = '"' + val.replace( '"', '\"' ) + '"';
      // }

      return this.trigger( 'query', key, val, ev );
    },

    useGroup : function ( ev ) {
      var $target = $( ev.target );

      var key = $target.data( 'key' );
      var val = $target.data( 'value' );

      this.trigger( 'query', key, val );

      if ( $target.data( 'execute-on-click' ) !== undefined ) {
        return this.trigger( 'exec', ev );
      }

      return this;
    },

    showModal : function ( $target, key ) {
      var $modal = this.$el.find( '.lumberjack__modal-window' ).removeClass( 'is-hidden' );

      $modal.find( '.lumberjack__modal-window__heading' ).text( key );
      $modal.find( '.lumberjack__modal-window__content' ).html( $target.html() );

      var width  = this.$el.width() - $modal.width();
      var height = this.$el.height() - $modal.height();

      $modal.css( {
        top  : height / 2,
        left : width / 2
      } );
    },

    hideModal : function ( ev ) {
      $( ev.target )
        .closest( '.lumberjack__modal-window' )
          .addClass( 'is-hidden' );
    },

    pinCell : function ( ev ) {
      $( ev.target ).toggleClass( 'pinned' );
    }
  } );
} );
