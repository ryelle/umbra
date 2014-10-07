<?php
/**
 * @package Umbra
 */
if ( 0 === strpos( $post->post_mime_type, 'video' ) ) {
	$format = 'video';
} elseif ( 0 === strpos( $post->post_mime_type, 'audio' ) ) {
	$format = 'audio';
} elseif ( ! wp_attachment_is_image() ) {
	$format = 'standard';
} else {
	$format = 'image';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( wp_attachment_is_image() ) : ?>
	<div class="entry-image">
		<?php echo wp_get_attachment_link( 0, 'post-thumbnail', false ); ?>
	</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php umbra_posted_on(); ?>
			<div class="entry-icons">
				<i class="genericon genericon-<?php echo $format; ?>"></i>
			</div><!-- .entry-icons -->
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			$caption = get_the_excerpt();

			if ( 0 === strpos( $post->post_mime_type, 'video' ) ) {
				$meta = wp_get_attachment_metadata( get_the_ID() );
				$atts = array( 'src' => wp_get_attachment_url() );
				if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
					$atts['width'] = (int) $meta['width'];
					$atts['height'] = (int) $meta['height'];
				}
				echo wp_video_shortcode( $atts );
			} elseif ( 0 === strpos( $post->post_mime_type, 'audio' ) ) {
				echo wp_audio_shortcode( array( 'src' => wp_get_attachment_url() ) );
			} elseif ( ! wp_attachment_is_image() ) {
				echo wp_get_attachment_link( 0, 'post-thumbnail', false, true );
			}

			if ( $caption ) {
				echo '<div class="wp-caption">';
				printf( '<p class="wp-caption-text">%s</p>', $caption );
				echo '</div>';
			}

		?>

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'umbra' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
