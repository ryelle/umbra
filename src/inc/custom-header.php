<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Umbra
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses umbra_header_style()
 * @uses umbra_admin_header_style()
 * @uses umbra_admin_header_image()
 */
function umbra_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'umbra_custom_header_args', array(
		'default-image'          => umbra_get_default_header_image(),
		'width'                  => 170,
		'height'                 => 170,
		'wp-head-callback'       => '__return_false',
		'admin-head-callback'    => 'umbra_admin_header_style',
		'admin-preview-callback' => 'umbra_admin_header_image',
		// Color is not customizable
		'header-text'            => false,
	) ) );
}
add_action( 'after_setup_theme', 'umbra_custom_header_setup' );

/**
 * A default header image
 *
 * Use the admin email's gravatar as the default header image.
 */
function umbra_get_default_header_image() {

	// Get default from Discussion Settings.
	$default = get_option( 'avatar_default', 'mystery' ); // Mystery man default
	if ( 'mystery' == $default ) {
		$default = 'mm';
	} elseif ( 'gravatar_default' == $default ) {
		$default = '';
	}

	$protocol = ( is_ssl() ) ? 'https://secure.' : 'http://';
	$url = sprintf( '%1$sgravatar.com/avatar/%2$s/', $protocol, md5( get_option( 'admin_email' ) ) );
	$url = add_query_arg( array(
		's' => 170,
		'd' => urlencode( $default ),
	), $url );

	return esc_url_raw( $url );
} // umbra_get_default_header_image

if ( ! function_exists( 'umbra_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see umbra_custom_header_setup().
 */
function umbra_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			padding: 60px 45px;
			width: 170px;
			border: none;
			background-color: #424046;
			text-align: center;
		}
		#headimg h1 {
			font: bold italic 24px Vollkorn, serif;
		}
		#headimg h1 a {
			color: #e9e3f4;
			text-decoration: none;
		}
		#desc {
			font: 12px Montserrat, sans-serif;
			color: #8f8a99;
		}
		#headimg img {
		}
	</style>
<?php
}
endif; // umbra_admin_header_style

if ( ! function_exists( 'umbra_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see umbra_custom_header_setup().
 */
function umbra_admin_header_image() { ?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
		<h1 class="displaying-header-text"><a id="name" onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"><?php bloginfo( 'description' ); ?></div>
	</div>
<?php
}
endif; // umbra_admin_header_image
