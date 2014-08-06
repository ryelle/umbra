<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Umbra
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'umbra' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( "Sorry, but it looks like we can&rsquo;t find what you&rsquo;re looking for! Try checking in the navigation menu, or using the search box below.", 'umbra' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>