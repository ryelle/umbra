/**
* comment-fix.js
*
* Quick JS to move the moderation note to below the comment content.
*/
( function( $ ) {
	var $notes = $('.comment-awaiting-moderation');

	if ( $notes.length > 0 ){

		$notes.each( function( index, element ){
			var $destination = $(element).closest( '.comment-body' ).find('.comment-content'),
				$note        = $(element).detach();

			$note.appendTo( $destination );
		});

	}

})( jQuery );