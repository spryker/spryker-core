/*global
  require: false,
  describe: false,
  it: false,
  expect: false,
  jasmine: false
*/
require([
  'parser'
], function( Parser ){
  'use strict';

  describe( 'Lucene Parser', function(){
    it( 'Should be an object', function(){
      expect( Parser ).toEqual( jasmine.any( Object ));
    });
  });

  // describe( 'Saatchi Login', function(){
  //   it( 'Should be an object', function(){
  //     expect( Login ).toEqual( jasmine.any( Object ));
  //   });

  //   describe( 'init', function(){
  //     it( 'Should contain init method', function(){
  //       expect( Login.init ).toEqual( jasmine.any( Function ));
  //     });
  //   });
  // });
});
