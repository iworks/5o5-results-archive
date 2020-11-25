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
$description = get_bloginfo( 'description', 'display' );
if ( $description || is_customize_preview() ) {
?>
                    <p class="site-description"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php } ?>
            </div><!-- .site-branding -->
			<nav id="site-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'opi-jobs' ); ?></button>
                <div id="primary-menu">
<?php
								if ( has_nav_menu( 'primary' ) ) {

									wp_nav_menu(
										array(
											'container'  => '',
											'items_wrap' => '%3$s',
											'theme_location' => 'primary',
										)
                                    );
                                }
?>
				</div>
			</nav>
        </div>
    </div>
</header>

