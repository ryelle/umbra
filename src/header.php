<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Umbra
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.min.js"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
<a class="skip-link screen-reader-text" href="#primary"><?php _e( 'Skip to content', 'umbra' ); ?></a>
<div id="page" class="hfeed site">

	<a href="#" class="menu-toggle"><?php _e( 'Menu', 'umbra' ); ?></a>

	<div id="content" class="site-content">

		<div class="site-header-bg"></div>
		<header id="masthead" class="site-header" role="banner">
			<?php if ( get_header_image() ) : ?>
			<div class="site-image">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" alt="" class="no-grav">
				</a>
			</div>
			<?php endif; // End header image check. ?>

			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation -->

			<nav id="social-navigation" class="social-navigation main-navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'social', 'menu_class' => 'social-menu' ) ); ?>
			</nav><!-- #social-navigation -->
		</header><!-- #masthead -->
