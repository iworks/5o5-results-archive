<?php

$args = wp_parse_args(
	$args,
	[
		'buttons' => 'hide',
	]
);

?><header id="masthead" class="site-header">
	<div class="site-header-one-container">
		<div class="site-header-one">
			<div class="site-branding">
<?php
the_custom_logo();
if ( is_front_page() && is_home() ) {
	?>
<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<?php
} else {
	?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
	<?php
}
?>
<p class="site-description">
    <strong><?php esc_html_e( 'sailors, boats & results', '5o5-results-archive' ); ?></strong>
    <span><?php esc_html_e( 'through the ages', '5o5-results-archive' ); ?></span>
</p>
            </div><!-- .site-branding -->
            <div class="search"><?php get_search_form(); ?></div>
			<nav id="site-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', '5o5-results-archive' ); ?></button>
                <div id="primary-menu">
<?php if ( has_nav_menu( 'primary' ) ) { ?>
                    <ul class="primary-menu reset-list-style">
<?php
									wp_nav_menu(
										array(
											'container'  => '',
											'items_wrap' => '%3$s',
											'theme_location' => 'primary',
										)
                                    );
                                }
?>
                    </ul>
				</div>
			</nav>
        </div>
    </div>
</header>

