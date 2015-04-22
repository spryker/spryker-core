/*global
  define: false,
  CustomEvent: false
*/
define([
  'd3',

  'chronos'
], function( d3, Chronos ){
  'use strict';

  var margin = {
    top    : 5,
    right  : 20,
    bottom : 20,
    left   : 35
  };

  function addBrush( svgEl, svg, x, height ) {

    var brush = d3.svg.brush();

    brush
      .x( x )
      // .on( 'brushstart', function () {
      //   brushStart.text( '' );
      //   brushEnd.text( '' );
      // } )
      .on( 'brush', function () {
        var extent = brush.extent();

        brushStart.text( Chronos.printUTC( extent[ 0 ] ) );
        brushEnd.text( Chronos.printUTC( extent[ 1 ] ) );

      //   var extentEl = brushBox.select( '.extent' );

      //   var x = + extentEl.attr( 'x' ) + extentEl.attr( 'width' ) / 2;

      //   brushApply
      //     .attr( 'transform', 'translate(' + x + ',0)' );
      } )
      .on( 'brushend', function () {
        var ev = new CustomEvent( 'brushend', {
          detail     : {
            extent : brush.extent()
          },
          bubbles    : true,
          cancelable : true,
          target     : svgEl
        } );

        svgEl.dispatchEvent( ev );
      } );

    var brushBox = svg.append( 'g' )
      .attr( 'class', 'brush' )
      .call( brush );

    brushBox
      .selectAll( 'rect' )
        .attr( 'y', 0 )
        .attr( 'height', height );

    var brushStart = brushBox.select( '.resize.w' )
      .append( 'text' )
        .attr( 'transform', 'rotate(90), translate(2,-2)' );

    var brushEnd   = brushBox.select( '.resize.e' )
      .append( 'text' )
        .attr( 'transform', 'rotate(90), translate(' + ( height - 2 ) + ',10)' );

    // var brushApply = brushBox.append( 'g' );
    //   // .attr( 'y', height )

    // brushApply.append( 'rect' )
    //   .attr( 'width', 20 )
    //   .attr( 'height', 20 )
    //   .attr( 'x', -10 )
    //   .attr( 'y', -20 );
  }

  return function ( svgEl, data, options ){

    // console.log( data, data.length );

    var width  = ( options.width  || 600 ) - margin.left - margin.right;
    var height = ( options.height || 150 ) - margin.top - margin.bottom;

    var svg = d3.select( svgEl )
      .attr( 'width', width + margin.left + margin.right )
      .attr( 'height', height + margin.top + margin.bottom )
      .append( 'g' )
        .attr( 'transform', 'translate(' + margin.left + ',' + margin.top + ')' );

    var x = d3.time.scale.utc()
      .range( [ 0, width ] );

    var y = d3.scale.sqrt()
      .range( [ height, 0 ] );

    var xAxis = d3.svg.axis()
      .scale( x )
      .orient( 'bottom' )
      .tickSize( - height )
      .tickSubdivide( true );

    var yAxis = d3.svg.axis()
      .scale( y )
      .orient( 'left' )
      .ticks( 4 )
      .tickSize( - width )
      .tickSubdivide( true );

    var area = d3.svg.area()
      .interpolate( 'basis' )
      .x( function ( d ) {
        return x( d.date );
      } )
      .y0( height )
      .y1( function ( d ) {
        return y( d.value );
      } );

    if ( options.timespan ) {
      x.domain( d3.extent( options.timespan, function ( d ) {
        return new Date( d );
      } ) );
    } else {
      x.domain( d3.extent( data, function ( d ) {
        return d.date;
      } ) );
    }

    y.domain( [ 0.1, 1.2 * d3.max( data, function ( d ) {
      return d.value;
    } ) ] );

    svg.append( 'path' )
      .datum( data )
        .attr( 'class', 'histogram-shadow' )
        .attr( 'd', area );

    svg.append( 'g' )
      .attr( 'class', 'x axis' )
      .attr( 'transform', 'translate(0,' + height + ' )' )
      .call( xAxis );

    svg.append( 'g' )
      .attr( 'class', 'y axis' )
      .call( yAxis );

    svg.selectAll( '.histogram' )
      .data( data )
      .enter()
        .append( 'rect' )
        .attr( 'x', function ( d ) {
          return x( d.date );
        } )
        .attr( 'y', function ( d ) {
          return y( d.value );
        } )
        .attr( 'width', 1 )
        .attr( 'height', function ( d ) {
          return Math.max( 0, height - y( d.value ) );
        })
        // .attr( 'opacity', 0.1 )
        .attr( 'class', 'histogram' );

    addBrush( svgEl, svg, x, height );

    return this;
  };
});
