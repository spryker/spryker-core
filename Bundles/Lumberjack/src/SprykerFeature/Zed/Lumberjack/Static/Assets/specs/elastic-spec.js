/*global
  require: false,
  describe: false,
  it: false,
  expect: false,
  jasmine: false
*/
require([
  'elastic'
], function ( Elastic ) {
  'use strict';

  describe( 'Elastic helper', function () {
    it( 'Should be a function', function () {
      expect( Elastic ).toEqual( jasmine.any( Function ) );
    });

    it( 'Should parse an empty query', function () {
      expect( new Elastic( '' ).toString() ).toEqual( '' );
      expect( new Elastic().toString() ).toEqual( '' );
      expect( new Elastic( null ).toString() ).toEqual( '' );
    });

    it( 'Should parse a simple qurery', function () {
      expect( new Elastic( 'foo' ).toString() ).toEqual( 'foo' );
    });

    it( 'Should parse primitives', function () {
      expect( new Elastic( 1 ).toString() ).toEqual( '1' );
      expect( new Elastic( 0 ).toString() ).toEqual( '0' );

      expect( new Elastic( true ).toString() ).toEqual( 'true' );
      expect( new Elastic( false ).toString() ).toEqual( 'false' );
    });

    it( 'Should parse quoted values', function () {
      expect( new Elastic( '"foo bar"' ).toString() ).toEqual( '"foo bar"' );
      expect( new Elastic( 'foo:"foo bar" baz' ).toString() ).toEqual( 'foo:"foo bar" OR baz' );
      expect( new Elastic( 'foo:"foo \\"bar\\""' ).toString() ).toEqual( 'foo:"foo \\"bar\\""' );
      expect( new Elastic( 'foo:"{\\"foo\\":\\"bar\\"}"' ).toString() ).toEqual( 'foo:"{\\"foo\\":\\"bar\\"}"' );
    });

    it( 'Should parse operators', function () {
      var or  = new Elastic( 'foo OR bar' );
      var and = new Elastic( 'foo AND bar' );

      expect( or.toString() ).toEqual( 'foo OR bar' );
      expect( or.query.length ).toEqual( 3 );

      expect( and.toString() ).toEqual( 'foo AND bar' );
      expect( and.query.length ).toEqual( 3 );
    });

    var proto = Elastic.prototype;

    it ( 'Should fail for corrupted input', function () {
      expect( function() {
        new Elastic( 'foo:' );
      }).toThrow( proto.VALUELESS_KEY );

      expect( function() {
        new Elastic( ':' );
      }).toThrow( proto.UNQUOTED_VALUE );

      expect( function() {
        new Elastic( '"' );
      }).toThrow( proto.QUOTE_MISSMATCH );
    });

    it( 'Should add missing operators', function () {
      var foo = new Elastic( 'foo bar' );
      var bar = new Elastic( 'foo or bar' );
      var baz = new Elastic( 'foo AND and bar' );

      expect( foo.toString() ).toEqual( 'foo OR bar' );
      expect( bar.toString() ).toEqual( 'foo OR or OR bar' );
      expect( baz.toString() ).toEqual( 'foo AND and OR bar' );
    });

    it( 'Should handle replacing/adding values properly', function () {
      var replace1 = new Elastic( 'foo:bar' ).set( 'foo', 'baz' );
      var replace2 = new Elastic( 'foo:bar AND bar:baz OR baz:quux' ).set( 'bar', 'xxx' );
      var add1     = new Elastic( 'foo:bar' ).set( 'bar', 'baz' );
      var add2     = new Elastic( 'foo:bar' ).set( 'bar', 'baz', true );

      var empty    = new Elastic().set( 'foo', 'bar' );

      expect( replace1.toString() ).toEqual( 'foo:baz' );
      expect( replace2.toString() ).toEqual( 'foo:bar AND bar:xxx OR baz:quux' );

      expect( add1.toString() ).toEqual( 'foo:bar OR bar:baz' );
      expect( add2.toString() ).toEqual( 'foo:bar AND bar:baz' );

      expect( empty.toString() ).toEqual( 'foo:bar' );
    });

  });
});
