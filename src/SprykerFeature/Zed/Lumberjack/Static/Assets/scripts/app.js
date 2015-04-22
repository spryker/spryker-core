/* global
  define: false
*/
define([
  'backbone',
  'routers/router',
  'views/app'
], function( Backbone, Router, AppView ){
  'use strict';

  // start the app first
  new AppView();

  // then init the routing
  Backbone.history.start();
});
