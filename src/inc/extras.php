<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Umbra
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function umbra_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'umbra_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function umbra_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ! is_singular() || ! has_post_thumbnail() ){
		$classes[] = 'no-image';
	}

	return $classes;
}
add_filter( 'body_class', 'umbra_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array  $classes An array of post classes.
 * @param string $class   A comma-separated list of additional classes added to the post.
 * @param int    $post_id The post ID.
 * @return array
 */
function umbra_post_class( $classes, $class, $post_id ) {
	$title = get_the_title( $post_id );

	if ( empty( $title ) ) {
		$classes[] = 'no-title';
	}

	return $classes;
}
add_filter( 'post_class', 'umbra_post_class', 10, 3 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function umbra_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'umbra' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'umbra_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function umbra_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'umbra_setup_author' );

/**
 * Unset the website field
 */
function umbra_comment_fields( $fields ){
	$commenter = wp_get_current_commenter();

	unset( $fields['url'] );

	$fields['author'] = sprintf(
		'<p class="comment-form-author"><label for="author">%1$s</label> <input id="author" name="author" type="text" value="%2$s" size="30" aria-required="true" placeholder="%1$s" /></p>',
		_x( 'Name', 'Label for commenter\'s name', 'umbra' ),
		esc_attr( $commenter['comment_author'] )
	);

	$fields['email'] = sprintf(
		'<p class="comment-form-email"><label for="email">%1$s</label><input id="email" name="email" type="email" value="%2$s" size="30" aria-required="true" placeholder="%1$s" /></p>',
		 _x( 'Email', 'Label for commenter\'s email', 'umbra' ),
		 esc_attr(  $commenter['comment_author_email'] )
	);

	return $fields;
}
add_filter( 'comment_form_default_fields', 'umbra_comment_fields' );
