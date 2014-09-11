/**
* navigation.js
*
* Handles toggling the navigation menu for small screens.
*/
( function( $ ) {

	/**
	 * Run this code when the #toggle-menu link has been tapped
	 * or clicked
	 */
	$( '.menu-toggle' ).on( 'touchstart click', function(e) {
		e.stopPropagation();
		e.preventDefault();

		var $body = $( 'body' ),
			$page = $( '#content' ),
			$sidebar = $( '#masthead, .site-header-bg' ),

			/* Cross browser support for CSS "transition end" event */
			transitionEnd = 'transitionend webkitTransitionEnd otransitionend MSTransitionEnd';

		/* When the toggle menu link is clicked, animation starts */
		$body.addClass( 'animating' );

		/**
		 * Determine the direction of the animation and
		 * add the correct direction class depending
		 * on whether the menu was already visible.
		 */
		if ( $body.hasClass( 'menu-visible' ) ) {
			$body.addClass( 'close' );
		} else {
			$body.addClass( 'open' );
		}

		/**
		 * When the animation (technically a CSS transition)
		 * has finished, remove all animating classes and
		 * either add or remove the "menu-visible" class
		 * depending whether it was visible or not previously.
		 */
		$page.on( transitionEnd, function() {
			$body
				.removeClass( 'animating open close' )
				.toggleClass( 'menu-visible' );

			$page.off( transitionEnd );
		});

	});

})( jQuery );