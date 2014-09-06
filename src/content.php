<?php
/**
 * @package Umbra
 */
$format = get_post_format();
if ( false === $format ) {
	$format = 'standard';
}
$title = get_the_title();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php umbra_posted_on(); ?>
			<div class="entry-icons">
				<?php if ( 'standard' != $format ) : ?><a href="<?php echo get_post_format_link( $format ); ?>"><?php endif; ?>
				<i class="genericon genericon-<?php echo esc_attr( $format ); ?>">
					<?php if ( 'standard' != $format ) : ?><span class="screen-reader-text"><?php echo esc_html( $format ); ?></span><?php endif; ?>
				</i>
				<?php if ( 'standard' != $format ) : ?></a><?php endif; ?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php
					$zero = '<i class="genericon genericon-comment"></i><span class="screen-reader-text">' . sprintf( __( 'Respond to %s', 'umbra' ), get_the_title() ) . '</span>';
					$one = '<i class="genericon genericon-comment"><span>1</span></i><span class="screen-reader-text">' . sprintf( __( 'Comment on %s', 'umbra' ), get_the_title() ) . '</span>';
					$many = '<i class="genericon genericon-comment"><span>%</span></i><span class="screen-reader-text">' . sprintf( __( 'Comments on %s', 'umbra' ), get_the_title() ) . '</span>';
					comments_popup_link( $zero, $one, $many );
				?>
				<?php endif; ?>
			</div><!-- .entry-icons -->
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( ! is_singular() ) : ?>
	<div class="entry-summary">
		<?php if ( comments_open() || pings_open() ) : ?>
		<span class="mobile-space"></span>
		<?php endif; ?>
		<?php if ( empty( $title ) ) : ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php endif; ?>
		<?php the_excerpt(); ?>
		<?php if ( empty( $title ) ) : ?></a><?php endif; ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'umbra' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'umbra' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( is_singular() ) : ?>
	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'umbra' ) );
				if ( $categories_list && umbra_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'umbra' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'umbra' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'umbra' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php edit_post_link( __( 'Edit', 'umbra' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
