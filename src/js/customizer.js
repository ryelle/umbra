/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			console.log( to );
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	var getCSS = _.debounce( function( to ){
		$.when(
			$.get( umbra.url + to.replace('#','') + '?no-cache' )
		).then( function( css ) {
			if ( ! $("#umbra-css").length ) {
				$('head').append( '<style id="umbra-css"><style>' );
			}

			$("#umbra-css").text(css);
		});
	}, 500);

	// Header text color.
	wp.customize( 'umbra_base_color', function( value ) {
		value.bind( getCSS );
	} );
} )( jQuery );
