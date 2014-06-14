<?php
/**
 * Dynamically pull colors from featured image
 *
 * @package Umbra
 */

class Umbra_ImageColors {

	private $white;
	private $black;

	function __construct() {
		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	function wp_head() {
		if ( ! class_exists( 'Jetpack' ) || ! current_theme_supports( 'tonesque' ) ) {
			return;
		}
		if ( ! is_singular() || ! has_post_thumbnail() ) {
			return;
		}

		$this->print_css();
	}

	public function print_css(){
		// Check cache, then generate as needed
		global $umbra_scheme;
		$umbra_scheme = $this->generate_css();
		get_template_part( 'partials/color-scheme' );
	}

	public function generate_css( $image_id = false ) {
		if ( ! $image_id ) {
			$image_id = get_post_thumbnail_id();
		}
		$color = $this->get_base_color( $image_id );

		$this->white = new Jetpack_Color('0xffffff');
		$this->black = new Jetpack_Color('0x000000');

		$scheme = array();
		$scheme_keys = array( 'body_background', 'title_color', 'site_title_color', 'link_color', 'hover_color', 'description_color', 'nav_text_color', 'nav_bg_color', 'nav_current_bg_color', 'sidebar_bg_color' );
		$scheme_keys = apply_filters( 'umbra_color_scheme_options', $scheme_keys );

		foreach ( $scheme_keys as $key ){
			$scheme[$key] = new Jetpack_Color( $color );
		}

		if ( $scheme['body_background']->getDistanceRgbFrom( $this->white ) > 250 ) {
			$scheme['body_background']->lighten( 25 );
		} elseif ( $scheme['body_background']->getDistanceRgbFrom( $this->white ) > 150 ) {
			$scheme['body_background']->lighten( 15 );
		}

		if ( $scheme['body_background']->getDistanceRgbFrom( $this->white ) < 150 ) {
			$scheme['title_color']->desaturate( 25 )->darken( 15 );
			$scheme['link_color']->desaturate( 25 )->darken( 10 );
			$scheme['hover_color']->desaturate( 25 )->darken( 20 );
		} else {
			$scheme['title_color']->desaturate( 25 )->darken( 5 );
			$scheme['link_color']->desaturate( 25 )->lighten( 7 );
			$scheme['hover_color']->desaturate( 25 );
		}

		$scheme['sidebar_bg_color']->darken( 10 )->desaturate( 15 );
		if ( 150 < $scheme['sidebar_bg_color']->getDistanceRgbFrom( $this->black ) ) {
			$scheme['site_title_color']->lighten( 13 );
			$scheme['description_color']->desaturate( 25 )->lighten( 15 );
			$scheme['nav_text_color']->lighten( 25 );
			$scheme['nav_bg_color']->darken( 20 )->desaturate( 15 );
			$scheme['nav_current_bg_color']->darken( 25 )->desaturate( 25 );
		} else {
			$scheme['site_title_color']->lighten( 35 );
			$scheme['description_color']->lighten( 30 );
			$scheme['nav_text_color']->lighten( 60 );
			$scheme['nav_bg_color']->lighten( 17 );
			$scheme['nav_current_bg_color']->lighten( 10 );
		}

		$scheme = apply_filters( 'umbra_color_scheme_colors', $scheme );

		return $scheme;
	}

	public function get_base_color( $image_id ){
		$thumb_attrs = wp_get_attachment_image_src( $image_id );
		$image_src = $thumb_attrs[0];
		$image = new Tonesque( $image_src );

		return $image->color();
	}
}

global $umbra_image_colors;
$umbra_image_colors = new Umbra_ImageColors();
