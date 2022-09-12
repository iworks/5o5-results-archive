<?php

$args = wp_parse_args(
	$args,
	array(
		'buttons' => 'hide',
	)
);

?><header id="masthead" class="site-header">
	<div class="site-header-one-container">
		<div class="site-header-one">
<?php
$tag = 'p';
if ( is_front_page() ) {
	$tag = 'h1';
}
?>
<<?php echo $tag; ?> class="site-branding">
	<?php
	if ( ! is_front_page() ) {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php } ?>
<span class="site-title"><?php bloginfo( 'name' ); ?></span>
<span class="site-description">
	<span>
		<strong><?php esc_html_e( 'sailors, boats & results', '5o5-results-archive' ); ?></strong>
		<span><?php esc_html_e( 'through the ages', '5o5-results-archive' ); ?></span>
	</span>
</span>
	<?php
	if ( ! is_front_page() ) {
		?>
		</a><?php } ?>
</<?php echo $tag; ?>>
			<div class="search"><?php get_search_form(); ?></div>
			<nav id="site-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', '5o5-results-archive' ); ?></button>
				<div id="primary-menu">
<?php
if ( has_nav_menu( 'primary' ) ) {
	wp_nav_menu(
		array(
			'container'      => false,
			'menu_class'     => 'primary-menu',
			'theme_location' => 'primary',
			'depth'          => 1,
		)
	);
}
?>
				</div>
			</nav>
			<a href="https://ko-fi.com/L4L54XNFS" title="Support me on ko-fi.com" class="button kofi-button" target="_blank"> <span class="kofitext"><?php esc_attr_e( 'Buy Me a Coffee', '5o5-results-archive' ); ?></span></a>
		</div>
	</div>
</header>

