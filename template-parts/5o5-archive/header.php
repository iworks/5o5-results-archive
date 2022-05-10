<?php
$archive_title    = '';
$archive_subtitle = '';
if ( is_search() ) {
	global $wp_query;

	$archive_title = sprintf(
		'%1$s %2$s',
		'<span class="color-accent">' . __( 'Search:', 'twentytwenty' ) . '</span>',
		'&ldquo;' . get_search_query() . '&rdquo;'
	);

	if ( $wp_query->found_posts ) {
		$archive_subtitle = sprintf(
			/* translators: %s: Number of search results. */
			_n(
				'We found %s result for your search.',
				'We found %s results for your search.',
				$wp_query->found_posts,
				'twentytwenty'
			),
			number_format_i18n( $wp_query->found_posts )
		);
	} else {
		$archive_subtitle = __( 'We could not find any results for your search. You can give it another try through the search form below.', 'twentytwenty' );
	}
} elseif ( ! is_home() ) {
	$archive_title    = get_the_archive_title();
	$archive_subtitle = get_the_archive_description();
}
if ( $archive_title || $archive_subtitle ) {
	?>

		<header class="archive-header has-text-align-center header-footer-group">

			<div class="archive-header-inner section-inner medium">

				<?php if ( $archive_title ) { ?>
					<h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
				<?php } ?>

				<?php if ( $archive_subtitle ) { ?>
					<div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
				<?php } ?>

			</div><!-- .archive-header-inner -->

		</header><!-- .archive-header -->

	<?php
}
