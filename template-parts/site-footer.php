<footer id="colophon" class="site-footer">
	<div class="site-info">
		<ul class="site-info-left">
			<li><?php echo apply_filters( 'int505_archive_last_update', '' ); ?></li>
<?php
if ( has_nav_menu( 'footer' ) ) {
	wp_nav_menu(
		array(
			'container'      => false,
			'theme_location' => 'footer',
			'depth'          => 1,
			'items_wrap'     => '%3$s',
		)
	);
}
?>
		</ul>
		<div class="site-info-right">
			<a class="int" href="https://www.int505.org/"><?php esc_html_e( 'International 5O5 Sailing', '5o5-results-archive' ); ?></a>
			<a class="pl" href="https://int505.pl/"><?php esc_html_e( 'Polish 5O5 Sailing', '5o5-results-archive' ); ?></a>
		</div>
	</div>
</footer>
