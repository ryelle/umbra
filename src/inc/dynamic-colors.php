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
		$css = $this->generate_css();
		// var_dump($css);
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
		$sass .= file_get_contents( get_template_directory() . '/sass/_dynamic-colors.scss' );
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