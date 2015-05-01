/* global
  define: false,
  window: false,
  document: false,
  console: false
*/
define([
  'jquery',
  'lodash',
  'backbone',

  'routers/router',
  'collections/queries',
  'views/query',

  'chronos',

  'component',
  'text!../../templates/app.dust',

  'parser',
  'elastic',

  'plugins/jquery.form-object'
], function ( $, _, Backbone,
     Router, Queries, QueryView,
     Chronos, Component, AppTemplate, Parser, Elastic ) {
  'use strict';

  var lastQuery;
  var initialHeight;
  var queryFormInput;
  var queryFormGhost;
  var typehinter = true;

  var ENTER_KEY = 13;

  var Keys = {
    enter    : 13,
    pageUp   : 33,
    pageDown : 34
  };

  function ctrlEnter( input ) {
    var val = input.value;

    if ( $.isNumeric( input.selectionStart ) && $.isNumeric( input.selectionEnd ) ) {
      var start = input.selectionStart;
      input.value = val.slice( 0, start ) + '\n' + val.slice( input.selectionEnd );
      input.selectionStart = input.selectionEnd = start + 1;
    } else if ( document.selection && document.selection.createRange ) {
      input.focus();
      var range = document.selection.createRange();
      range.text = '\r\n';
      range.collapse( false );
      range.select();
    } else {
      console.log( 'i forgot how to ctrl+enter...' );
    }
  }

  return Backbone.View.extend({
    el : '#lumberjack',

    initialize : function () {
      if ( ! this.$el.length ) {
        console.error( 'container for LJ %cnot found!', 'text-decoration:underline;' );

        return false;
      }

      Component.add( 'lumberjack__app', AppTemplate );

      this
        .addDOMReferences()
        .addQuerriesListeners()
        .toggleTypehinter( null, typehinter );

      var view = this;

      $( window ).on( {
        keydown : function ( ev ) {
          if ( ! /^body$/i.test( ev.target.nodeName ) ) {
            return ev;
          }

          var keyCode = ev.keyCode;

          if ( keyCode === Keys.pageUp || keyCode === Keys.pageDown ) {
            ev.preventDefault();
            ev.stopImmediatePropagation();

            view.handlePageUpDown( keyCode === Keys.pageUp );
            return false;
          }
        }
      } );
    },

    handlePageUpDown : function ( up ) {
      var $results = this.$output.children().not( ':first-child' );
      var $active  = $results.filter( '.active' ).first();
      var $next;

      $results.removeClass( 'active' );

      if ( ! $active.length ) {
        $next = up ? $results.last() : $results.first();
      } else if ( !up ) {
        $next = $active.next();
      } else {
        $next = $active.prev();
      }

      var scrollTop = 0;

      if ( $next && $next.length ) {
        $next.addClass( 'active' );

        scrollTop = $next.offset().top - this.$queryFormInput.outerHeight();
      }

      $( 'body' ).stop().animate({
        scrollTop : scrollTop
      }, 400 );

      // console.log( up );
    },

    events : {
      'change .lumberjack__command-select' : 'focusQuery',

      'input .lumberjack__input'   : 'cloneInput',
      'keyup .lumberjack__input'   : 'cloneInput',
      'keydown .lumberjack__input' : 'submitIfEnter',
      'blur  .lumberjack__input'   : 'syntaxHighlight',

      'submit form' : 'submitForm',

      'click .lumberjack__typehinter-toggle'       : 'toggleTypehinter',
      'click .lumberjack__settings-bar__toggle'    : 'toggleSettingsBar',
      'click .lumberjack__settings-bar__fields dt' : 'resetSettingsFields',

      'click .lumberjack__query a' : 'triggerRouter'
    },

    addDOMReferences : function () {
      var view = this;

      view.$el.addClass( 'lumberjack__container' );
      // this.$el.html( this.template() );
      Component.render( 'lumberjack__app', {}, function ( err, html ) {
        view.$el.html( html );
      } );

      view.$form           = view.$( '.lumberjack__query-form' );
      view.$queryFormInput = view.$form.find( '[name="q"]' );
      view.$queryFormGhost = view.$queryFormInput.next();

      view.$settingsBar = view.$( '.lumberjack__settings-bar' );

      view.$timespanFrom = view.$form.find( '[name="start"]' );
      view.$timespanTo   = view.$form.find( '[name="end"]' );

      queryFormInput  = view.$queryFormInput.get( 0 );
      queryFormGhost  = view.$queryFormGhost.get( 0 );

      view.$output = view.$( '.lumberjack__output' );

      initialHeight = queryFormInput.offsetHeight;
      lastQuery     = queryFormInput.value;

      return view;
    },

    addQuerriesListeners : function () {
      Queries.on({
        add : function ( query ) {
          var view = new QueryView({
            model : query
          });

          this.addViewListeners( view );
          this.$output.prepend( view.render().el );
        },
        'change:timespan' : function ( model, timespan ) {
          // show settings bar in "timespan-mode" to indicate the date change, if needed
          if ( ! this.$settingsBar.hasClass( 'active' ) ) {
            this.$settingsBar.addClass( 'active-timespan' );
          }

          this.$timespanFrom.val( Chronos.printUTC( timespan[ 0 ] ) );
          this.$timespanTo.val( Chronos.printUTC( timespan[ 1 ] ) );
          this.$queryFormInput.val( model.get( 'query' ) );

          this.syntaxHighlight();

          // console.log( a, b, c );
        }
      }, this );

      return this;
    },

    addViewListeners : function ( view ) {
      view.on( {
        'query' : this.setQuery,
        'exec'  : this.submitForm
      }, this );
    },

    focusQuery : function ( ev ) {
      // prevent that nasty extra new line, if selecting with keyboard
      ev.target.parentNode.setAttribute( 'data-value', ev.target.value );

      window.setTimeout( function () {
        queryFormInput.focus();
      }, 40 );

      return ev;
    },

    cloneInput : function ( ev, force ) {
      var query = queryFormInput.value;

      if ( !force && lastQuery === query ) {
        return ev;
      }

      queryFormGhost.innerHTML = query
        .replace( '<', '&gt;' )
        .replace( '>', '&lt;' )
        .replace( /\n/g, '<br>' ) + '&nbsp;';

      queryFormInput.style.height = ( queryFormGhost.offsetHeight ) + 'px';

      lastQuery = query;
    },

    submitIfEnter : function ( ev ) {
      if ( ev.keyCode === ENTER_KEY ) {
        if ( ! ev.ctrlKey ) {
          ev.preventDefault();
          ev.stopImmediatePropagation();

          ev.target.blur();

          this.$form.submit();

          return ev;
        }

        ctrlEnter( ev.target );
      }
    },

    submitForm : function ( ev ) {
      ev.preventDefault();

      var formObject = this.$form.formObject();

      if ( !formObject.q ) {
        formObject.q = '*';
        // return false;
      }

      console.log( formObject );

      var fragments = [];

      _.each( formObject, function ( value, key ) {
        // console.log( typeof value );
        if ( value !== undefined && value.length ) {
          if ( key === 'q' ) {
            value = '{' + encodeURI( value ) + '}';
          }
          fragments.push( key + ':' + value );
        }
      });

      this.$queryFormInput.val( '' ).focus();
      // this.$queryFormGhost.html( '' );
      this.syntaxHighlight();

      this.$settingsBar.removeClass( 'active active-timespan' );

      Router.navigate( '#/' + fragments.join( '|' ), {
        trigger : true
      });
    },

    syntaxHighlight : function ( ev ) {
      var query = queryFormInput.value;

      if ( ! query ) {
        queryFormGhost.innerHTML = '\n';
      }

      if ( ! typehinter || ! query  ) {
        return ev;
      }

      try {
        var parsedQuery = new Elastic( query );

        queryFormGhost.innerHTML = parsedQuery.toHTML();
        queryFormInput.value     = parsedQuery.toString();
      } catch ( e ) {
        window.alert( 'query parse error\n\n' + e );
      }
    },

    toggleTypehinter : function ( ev, bool ) {
      if ( ev ) {
        ev.preventDefault();
      }

      if ( bool !== undefined ) {
        typehinter = !! bool;
      } else {
        typehinter = !typehinter;
      }

      this.$form.find( '.lumberjack__typehinter-toggle' )
        .toggleClass( 'active', typehinter );

      if ( typehinter ) {
        this.syntaxHighlight();
      } else {
        this.cloneInput( null, true );
      }
    },

    toggleSettingsBar : function ( ev ) {
      if ( ev ) {
        ev.preventDefault();
      }

      this.$settingsBar
        .toggleClass( 'active' )
        .removeClass( 'active-timespan' );
    },

    resetSettingsFields : function ( ev ) {
      ev.preventDefault();

      // get all the fields with same class of container and reset ( clear? ) them
      $( ev.target )
        .parent()
          .children( '.' + ev.target.className )
            .find( ':input' ).each( function () {
              if ( /checkbox|radio/i.test( this.type ) ) {
                this.checked = false;
                return this;
              }

              var $this = $( this );

              $this.val( $this.data( 'default-value' ) || '' );

              return this;
            } );
    },

    setQuery : function ( key, val, ev ) {
      var query;

      try {
        if ( ev && ( ev.metaKey || ev.altKey ) ) {
          query = new Elastic( this.$queryFormInput.val() ).set( key, val, ev.shiftKey );
        } else {
          query = new Elastic().set( key, val );
        }

        this.$queryFormGhost.html( query.toHTML() );
        this.$queryFormInput.focus().val( query.toString() );
      } catch ( e ) {
        window.alert( 'can\'t extend the query - query parse error\n\n' + e );
      }

    },

    triggerRouter : function ( ev ) {
      ev.preventDefault();

      if ( ev.metaKey || ev.altKey ) {
        return this.$queryFormInput.focus().val( ev.target.textContent );
      }

      Router.navigate( ev.target.getAttribute( 'href' ), {
        trigger : true
      });
    }
  });
});
