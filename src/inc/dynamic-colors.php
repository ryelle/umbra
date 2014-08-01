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
			return;
		}

		$this->print_css();
	}

	public function print_css(){
		$image_id = get_post_thumbnail_id();
		$css = get_transient( $this->cache_prefix . $image_id );

		if ( empty( $css ) ) {
			$css = $this->generate_css();
			set_transient( $this->cache_prefix . $image_id, $css );
		}

		if ( $css ) {
			printf( '<style>%s</style>', $css );
		}
	}

	public function generate_css( $image_id = false ) {
		if ( ! $image_id ) {
			$image_id = get_post_thumbnail_id();
		}
		$color = $this->get_base_color( $image_id );

		if ( ! class_exists( 'Jetpack_Custom_CSS' ) ) {
			require Jetpack::get_module_path( 'custom-css' );
		}
		$sass = '$base-color: #'. $color .';';
		$sass .= file_get_contents( get_template_directory() . '/inc/dynamic-colors.scss' );
		$css = Jetpack_Custom_CSS::minify( $sass, 'sass' );

		return $css;
	}

	public function ajax_css(){
		if ( ! get_query_var( 'umbra' ) ){
			return;
		}

		$color = get_query_var( 'umbra_color' );
		$valid_color = preg_match( '/^[0-9a-f]{3}([0-9a-f]{3})?$/i', $color );
		if ( ! $color || ! $valid_color ) {
			return;
		}

		// Cached under `umbra_css_color_123456`
		$css = get_transient( $this->cache_prefix . 'color_' . $color );
		if ( empty( $css ) ) {
			if ( ! class_exists( 'Jetpack_Custom_CSS' ) ) {
				require Jetpack::get_module_path( 'custom-css' );
			}
			$sass = '$base-color: #'. $color .';';
			$sass .= file_get_contents( get_template_directory() . '/inc/dynamic-colors.scss' );
			$css = Jetpack_Custom_CSS::minify( $sass, 'sass' );

			set_transient( $this->cache_prefix . 'color_' . $color, $css );
		}

		//
		echo $css;
		die();
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
