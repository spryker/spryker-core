/*global
  define: false
*/
define([
  'backbone',
  'models/query'
], function ( Backbone, QueryModel ) {
  'use strict';

  var Queries = Backbone.Collection.extend({
    model : QueryModel
  });

  return new Queries();
});
