<?php
/**
 * Dynamically pull colors from featured image
 *
 * @package Umbra
 */

class Umbra_ImageColors {

	private $cache_prefix = 'umbra_css_';

	function __construct() {
		add_action( 'init',              array( $this, 'rewrites' ) );
		add_action( 'template_redirect', array( $this, 'ajax_css' ) );

		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	public function rewrites(){
		if ( ! class_exists( 'Jetpack' ) || ! current_theme_supports( 'tonesque' ) ) {
			return;
		}

		add_rewrite_tag( '%umbra%', '([^&]+)' );
		add_rewrite_tag( '%umbra_color%', '([^&]+)' );
		add_rewrite_rule( '^umbra-css/([^/]+)/?', 'index.php?umbra=true&umbra_color=$matches[1]', 'top' );
	}

	public function wp_head() {
		if ( ! class_exists( 'Jetpack' ) || ! current_theme_supports( 'tonesque' ) ) {
			return;
		}

		if ( ! is_singular() || ! has_post_thumbnail() ) {
			$color = get_theme_mod( 'umbra_base_color', false );
			if ( $color ) {
				$this->print_css( $color );
			}
			return;
		}

		$can_tonesque = get_theme_mod( 'umbra_use_tonesque', true );
		if ( $can_tonesque ){
			$this->print_css();
		}
	}

	public function print_css( $color = false ){
		if ( ! $color ){
			$image_id = get_post_thumbnail_id();
			$color = $this->get_base_color( $image_id );
		}

		if ( $color ) {
			$css = $this->generate_css( $color );
		}

		if ( $css ) {
			printf( '<style id="umbra-css">%s</style>', $css );
		}
	}

	public function ajax_css(){
		if ( ! get_query_var( 'umbra' ) ){
			return;
		}

		$color = get_query_var( 'umbra_color' );
		$valid_color = preg_match( '/^[0-9a-f]{3}([0-9a-f]{3})?$/i', $color );
		if ( ( '424046' == $color ) || ! $color || ! $valid_color ) {
			return;
		}

		$css = $this->generate_css( $color );

		header("Content-type: text/css; charset: UTF-8");
		echo $css;
		die();
	}

	public function generate_css( $color ) {
		if ( ! class_exists( 'Jetpack_Custom_CSS' ) ) {
			require Jetpack::get_module_path( 'custom-css' );
		}
		$sass = '$base-color: #'. $color .';';
		$sass .= file_get_contents( get_template_directory() . '/inc/dynamic-colors.scss' );
		$css = Jetpack_Custom_CSS::minify( $sass, 'sass' );

		return $css;
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
