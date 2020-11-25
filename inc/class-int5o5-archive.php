<?php

abstract class OPI_Jobs {

	/**
	 * Theme url.
	 *
	 * @since 1.0.0
	 * @var string $option_name_icon Option name ICON.
	 */
	protected $url = '';

	/**
	 * Option name, used to save data on postmeta table.
	 *
	 * @since 1.0.0
	 * @var string $option_name_icon Option name ICON.
	 */
	protected $version = 'THEME_VERSION';

	/**
	 * Debug
	 *
	 * @since 1.0.0
	 * @var boolean $debug
	 */
	protected $debug = 'false';


	protected function __construct() {
		$child_version = wp_get_theme();
		$this->version = $child_version->Version;
		$this->url     = get_stylesheet_directory_uri();
		$this->debug   = defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	/**
	 * Check is login page
	 */
	protected function is_wp_login() {
		$ABSPATH_MY = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );
		return (
			(
				in_array( $ABSPATH_MY . 'wp-login.php', get_included_files() )
				|| in_array( $ABSPATH_MY . 'wp-register.php', get_included_files() )
			) || (
				isset( $_GLOBALS['pagenow'] )
				&& $GLOBALS['pagenow'] === 'wp-login.php'
			)
				|| $_SERVER['PHP_SELF'] == '/wp-login.php'
		);
	}
}

