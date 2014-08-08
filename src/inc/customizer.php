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
		'label'      => __( 'Pull page colors from the featured image' ),
		'section'    => 'colors',
		'type'       => 'checkbox',
	) );

	$wp_customize->add_setting( 'umbra_base_color', array(
		'default' => '424046',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize, 'umbra_base_color', array(
		'label'   => __( 'Default Colors', 'umbra' ),
		'section' => 'colors',
	) ) );
}
add_action( 'customize_register', 'umbra_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function umbra_customize_preview_js() {
	wp_enqueue_script( 'umbra_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
	wp_localize_script( 'umbra_customizer', 'umbra', array( 'url' => home_url( '/umbra-css/') ) );
}
add_action( 'customize_preview_init', 'umbra_customize_preview_js' );



