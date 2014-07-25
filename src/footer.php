<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Umbra
 */
?>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info">
				<p><a href="<?php echo esc_url( __( 'http://wordpress.org/', 'umbra' ) ); ?>"><?php printf( __( 'Proudly powered by%s', 'umbra' ), '&nbsp;WordPress' ); ?></a></p>
				<p><?php printf( __( 'Theme: %1$s by %2$s.', 'umbra' ), 'Umbra', '<a href="http://themes.redradar.net" rel="designer">Kelly&nbsp;Dwan & Mel&nbsp;Choyce</a>' ); ?></p>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->

	</div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
