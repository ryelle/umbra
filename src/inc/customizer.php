<?php
/**
 * Umbra Theme Customizer
 *
 * @package Umbra
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function umbra_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_setting( 'umbra_use_tonesque', array(
		'default' => true
	) );

	$wp_customize->add_control( 'umbra_use_tonesque', array(
		'label'      => __( 'Create color scheme from first image', 'umbra' ),
		'section'    => 'colors',
		'type'       => 'checkbox',
	) );

	$wp_customize->add_setting( 'umbra_base_color', array(
		'default' => '424046',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control(new Umbra_Customize_Color_Control( $wp_customize, 'umbra_base_color', array(
		'label'   => __( 'Default Colors', 'umbra' ),
		'section' => 'colors',
	) ) );
}
add_action( 'customize_register', 'umbra_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function umbra_customize_preview_js() {
	wp_enqueue_script( 'umbra_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview', 'underscore' ), '20130508', true );
	wp_localize_script( 'umbra_customizer', 'umbra', array( 'url' => home_url( '/umbra-css/') ) );
}
add_action( 'customize_preview_init', 'umbra_customize_preview_js' );


if ( class_exists( 'WP_Customize_Color_Control' ) ) {
	/**
	 * Create the Umbra_Customize_Color_Control based on WP_Customize_Color_Control.
	 *
	 * The new control inherits all methods of WP_Customize_Color_Control, the only
	 * change is in adding the data-palettes attribute, so we can set our own palettes.
	 */
	class Umbra_Customize_Color_Control extends WP_Customize_Color_Control {
		public function render_content() {
			ob_start();
			parent::render_content();
			$output = ob_get_clean();
			$output = str_replace( '"', "'", $output );

			/**
			 * Filter the default palettes passed to the color picker.
			 *
			 * @since 0.1.0
			 *
			 * @param array  $palettes  The array of selected hex colors for Iris's palettes.
			 */
			$palettes = apply_filters( 'umbra_default_palettes', array( '#e8eaf0', '#e0bc64', '#98cc7e', '#6bc1ce', '#598bd1', '#332c7c', '#966c8e', '#222') );

			if ( is_array( $palettes ) ) {
				// Create a JSON string for the options
				$palettes = '["' . implode( '","', $palettes ) . '"]';
			} elseif ( 'false' == $palettes || ! $palettes ) {
				// Disable the palettes
				$palettes = 'false';
			} else {
				// Use the default palettes.
				$palettes = 'true';
			}

			echo str_replace( "color-picker-hex'", "color-picker-hex' data-palettes='$palettes'", $output );
		}
	}
}
