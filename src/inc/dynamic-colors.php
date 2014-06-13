<?php
/**
 * Dynamically pull colors from featured image
 *
 * @package Umbra
 */

class Umbra_ImageColors {

	function __construct() {
		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	function wp_head() {
		if ( ! is_singular() || ! has_post_thumbnail() ) {
			return;
		}

		$this->print_css();
	}

	public function print_css(){
		// Check cache, then generate as needed
		echo $this->generate_css();
	}

	public function generate_css( $image_id = false ) {
		if ( ! $image_id ) {
			$image_id = get_post_thumbnail_id();
		}
		$color = $this->get_base_color( $image_id );
	}

	public function get_base_color( $image_id ){
		$thumb_attrs = wp_get_attachment_image_src(  );
		$image_src = $thumb_attrs[0];

		require get_template_directory() . '/inc/tonesque/tonesque.php';
		$image = new Tonesque( $image_src );

		return $base_color = $image->color();
	}

}

global $umbra_image_colors;
$umbra_image_colors = new Umbra_ImageColors();








	$body_background = new Color( '#' . $base_color, 'hex' );
	if ( 250 < $body_background->getDistanceRgbFrom( new Color('#ffffff', 'hex') ) ) {
		$body_background = $body_background->lighten( 25 );
	} elseif ( 150 < $body_background->getDistanceRgbFrom( new Color('#ffffff', 'hex') ) ) {
		$body_background = $body_background->lighten( 15 );
	}

	$title_color = new Color( '#' . $base_color, 'hex' );
	$site_title_color = new Color( '#' . $base_color, 'hex' );
	$link_color = new Color( '#' . $base_color, 'hex' );
	$hover_color = new Color( '#' . $base_color, 'hex' );
	$description_color = new Color( '#' . $base_color, 'hex' );
	$nav_text_color = new Color( '#' . $base_color, 'hex' );
	$nav_bg_color = new Color( '#' . $base_color, 'hex' );
	$nav_current_bg_color = new Color( '#' . $base_color, 'hex' );
	$sidebar_bg_color = new Color( '#' . $base_color, 'hex' );

	$sidebar_bg_color = $sidebar_bg_color->darken( 10 )->desaturate( 15 );

	if ( 150 < $sidebar_bg_color->getDistanceRgbFrom( new Color('#000000', 'hex') ) ) {
		$site_title_color = $site_title_color->lighten( 13 );
		$description_color = $description_color->desaturate( 25 )->lighten( 15 );
		$nav_text_color = $nav_text_color->lighten( 25 );
		$nav_bg_color = $nav_bg_color->darken( 20 )->desaturate( 15 );
		$nav_current_bg_color = $nav_current_bg_color->darken( 25 )->desaturate( 25 );
	} else {
		$site_title_color = $site_title_color->lighten( 35 );
		$description_color = $description_color->lighten( 30 );
		$nav_text_color = $nav_text_color->lighten( 60 );
		$nav_bg_color = $nav_bg_color->lighten( 17 );
		$nav_current_bg_color = $nav_current_bg_color->lighten( 10 );
	}

	if ( 150 > $body_background->getDistanceRgbFrom( new Color('#ffffff', 'hex') ) ) {
		$title_color = $title_color->desaturate( 25 )->darken( 15 );
		$link_color = $link_color->desaturate( 25 )->darken( 10 );
		$hover_color = $hover_color->desaturate( 25 )->darken( 20 );
	} else {
		$title_color = $title_color->desaturate( 25 )->darken( 5 );
		$link_color = $link_color->desaturate( 25 )->lighten( 7 );
		$hover_color = $hover_color->desaturate( 25 );
	}

}

