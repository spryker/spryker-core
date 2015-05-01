/* global
  define:false
*/
define([
  'backbone',
  'collections/queries'
], function ( Backbone, Queries ) {
  'use strict';

  return new ( Backbone.Router.extend({
    routes : {
      '*query' : function ( hash ) {
        if ( ! hash ) {
          return false;
        }

        Queries.add({
          hash : hash
        });
      }
    }
  }) )();
});
