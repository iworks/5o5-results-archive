<?php
/**
 * Template part for displaying iworks_fleet_person
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package 5o5-results-archive
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) {
			$prefix = '<h1 class="entry-title">';
			$hash   = get_post_meta( get_the_ID(), 'iworks_fleet_contact_gravatar', true );
			if ( ! empty( $hash ) ) {
				$size   = 128;
				$prefix = sprintf(
					'<h1 class="entry-title entry-title-gravatar"><img src="%2$s" class="avatar avatar-%1$d photo" width="%1$d" height="%1$d" loading="lazy" decoding="async" />',
					$size,
					add_query_arg(
						array(
							's' => $size,
							'd' => 'mm',
							'r' => 'g',
						),
						'https://gravatar.com/avatar/' . $hash
					)
				);
			}
			the_title( $prefix, '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				int505_archive_posted_on();
				int505_archive_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', '5o5-results-archive' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', '5o5-results-archive' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php int505_archive_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
