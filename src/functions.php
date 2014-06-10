<?php
/**
 * Umbra functions and definitions
 *
 * @package Umbra
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'umbra_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function umbra_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Umbra, use a find and replace
	 * to change 'umbra' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'umbra', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'umbra' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'umbra_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
endif; // umbra_setup
add_action( 'after_setup_theme', 'umbra_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function umbra_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'umbra' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'umbra_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function umbra_scripts() {
	wp_enqueue_style( 'umbra-style', get_stylesheet_uri() );

	wp_enqueue_script( 'umbra-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'umbra-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'umbra_scripts' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Vollkorn, Fenix and Montserrat by default is
 * localized. For languages that use characters not supported by either
 * font, the font can be disabled.
 *
 * @since Umbra 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function umbra_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Vollkorn, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$vollkorn = _x( 'on', 'Vollkorn font: on or off', 'umbra' );

	/* Translators: If there are characters in your language that are not
	 * supported by Fenix, translate this to 'off'. Do not translate into
	 * your own language.
	 */
	$fenix = _x( 'on', 'Fenix font: on or off', 'umbra' );

	/* Translators: If there are characters in your language that are not
	 * supported by Montserrat, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$montserrat = _x( 'on', 'Montserrat font: on or off', 'umbra' );

	if ( 'off' !== $vollkorn || 'off' !== $fenix || 'off' !== $montserrat ) {
		$font_families = array();

		if ( 'off' !== $vollkorn )
			$font_families[] = 'Vollkorn:700,700italic';

		if ( 'off' !== $fenix )
			$font_families[] = 'Fenix:400';

		if ( 'off' !== $montserrat )
			$font_families[] = 'Montserrat:400';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => implode( '|', $font_families ),
			'subset' => 'latin,latin-ext',
		);
		$fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Loads our special font CSS file.
 *
 * To disable in a child theme, use wp_dequeue_style()
 * function mytheme_dequeue_fonts() {
 *     wp_dequeue_style( 'umbra-fonts' );
 * }
 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
 *
 * @since Umbra 1.0
 *
 * @return void
 */
function umbra_fonts() {
	$fonts_url = umbra_fonts_url();
	if ( ! empty( $fonts_url ) )
		wp_enqueue_style( 'umbra-fonts', esc_url_raw( $fonts_url ), array(), null );
}
add_action( 'wp_enqueue_scripts', 'umbra_fonts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 */
function umbra_admin_fonts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	umbra_fonts();

}
add_action( 'admin_enqueue_scripts', 'umbra_admin_fonts' );

/**
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses umbra_fonts_url() to get the Google Font stylesheet URL.
 *
 * @since Umbra 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string
 */
function umbra_mce_css( $mce_css ) {
	$fonts_url = umbra_fonts_url();

	if ( empty( $fonts_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $fonts_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'umbra_mce_css' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
