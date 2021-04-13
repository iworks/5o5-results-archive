<?php

require_once 'class-int5o5-archive.php';

class Int5o5_Archive_Theme extends Int5o5_Archive {

	/**
	 * Configuration for:
	 * /manifest.json
	 * /browserconfig.xml
	 *
	 * @since 1.0.0
	 */
	private $color_title      = '#003049';
	private $color_theme      = '#003049';
	private $color_background = '#f0f0ff';
	private $short_name       = '5o5 Archive';

	public function __construct() {
		parent::__construct();
		/**
		 * hooks
		 */
		add_action( 'wp_head', array( $this, 'html_head' ), PHP_INT_MAX );
		add_filter( 'get_site_icon_url', array( $this, 'get_site_default_icon_url' ), 10, 3 );
		add_filter( 'site_icon_meta_tags', array( $this, 'site_icon_meta_tags' ) );
		add_action( 'parse_request', array( $this, 'browserconfig_xml' ) );
		add_action( 'parse_request', array( $this, 'request_favicon' ) );
		add_action( 'init', array( $this, 'register_scripts' ) );
		add_action( 'save_post', array( $this, 'cache_clear' ) );
		add_filter( 'document_title_parts', array( $this, 'document_title_parts' ) );
		/**
		 * manifest.json
		 */
		if ( class_exists( 'iWorks_PWA' ) ) {
			add_filter( 'iworks_pwa_configuration', array( $this, 'iworks_pwa_configuration' ) );
			add_filter( 'iworks_pwa_offline_svg', array( $this, 'iworks_pwa_offline_svg' ) );
			add_filter( 'iworks_pwa_offline_file', array( $this, 'iworks_pwa_offline_file' ) );
			add_filter( 'iworks_pwa_offline_urls_set', array( $this, 'iworks_pwa_offline_urls_set' ) );
		} else {
			add_action( 'parse_request', array( $this, 'manifest_json' ) );
		}
		/**
		 * speed
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_scripts' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ), PHP_INT_MAX );
		add_action( 'wp_footer', array( $this, 'dequeue_styles' ), PHP_INT_MAX ); // WPML maybe_late_enqueue_template()
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		/**
		 * clear generaor type
		 */
		add_filter( 'get_the_generator_html', '__return_empty_string' );
		add_filter( 'get_the_generator_xhtml', '__return_empty_string' );
		add_filter( 'get_the_generator_atom', '__return_empty_string' );
		add_filter( 'get_the_generator_rss2', '__return_empty_string' );
		add_filter( 'get_the_generator_rdf', '__return_empty_string' );
		add_filter( 'get_the_generator_comment', '__return_empty_string' );
		add_filter( 'get_the_generator_export', '__return_empty_string' );
		/**
		 * theme
		 */
		add_filter( 'int505_archive_last_update', array( $this, 'get_last_update_string' ) );
		add_filter( 'int505_archive_last_results', array( $this, 'get_last_results_html' ) );
	}

	public function iworks_pwa_offline_urls_set( $set ) {
		$url = get_privacy_policy_url();
		if ( ! empty( $url ) ) {
			$set[] = $url;
		}
		$set[] = $this->url . '/assets/images/icons/favicon/favicon.ico';
		return $set;
	}

	public function iworks_pwa_offline_file( $data ) {
		return file_get_contents( $this->root . '/assets/pwa/offline.html' );
	}

	public function iworks_pwa_configuration( $data ) {
		return wp_parse_args( $this->manifest_json_data(), $data );
	}

	public function iworks_pwa_offline_svg( $svg ) {
		$svg = file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' );
		return $svg;
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( '5o5-results-archive', get_stylesheet_uri(), array(), $this->version );
		wp_enqueue_script( '5o5-results-archive' );
	}

	public function register_scripts() {
		/**
		 * theme
		 */
		wp_register_script(
			'5o5-results-archive',
			$this->url . sprintf( '/assets/scripts/frontend.%sjs', $this->debug ? '' : 'min.' ),
			array(),
			$this->version,
			true
		);
	}

	/**
	 * Get default favicon
	 *
	 * @since 1.0.0
	 */
	public function get_site_default_icon_url( $url, $size, $blog_id ) {
		if ( ! empty( $url ) ) {
			return $url;
		}
		return get_stylesheet_directory_uri() . '/assets/images/icons/favicon/apple-icon.png';
	}

	/**
	 * Remove scripts to improve speed
	 *
	 * @since 1.0.0
	 */
	public function dequeue_scripts() {
	}

	/**
	 * Remove styles to improve speed
	 *
	 * @since 1.4.0
	 */
	public function dequeue_styles() {
		if ( is_admin() ) {
			return;
		}
		wp_dequeue_style( 'wp-block-library' );
	}

	/**
	 *
	 * @since 1.0.0
	 */
	public function html_head() {
		/**
		 * turn off iOS phone number scraping
		 */
		echo '<meta name="format-detection" content="telephone=no" />' . PHP_EOL;
		echo '<meta name="msapplication-config" content="/browserconfig.xml" />' . PHP_EOL;
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * get url
	 *
	 * @since 1.0.0
	 */
	private function get_favicon_url( $icon, $extension = 'png' ) {
		$file = sprintf( '%s.%s', $icon, $extension );
		$file = sprintf( 'icons/favicon/%s?v=%s', sanitize_file_name( $file ), $this->version );
		$url  = $this->get_asset_url( $file );
		return wp_make_link_relative( esc_url( $url ) );
	}

	/**
	 * Favicons + meta settings
	 *
	 * @since 1.0.0
	 */
	public function site_icon_meta_tags( $meta_tags ) {
		$meta_tags = array();
		$icons     = array(
			'icon'    => array(
				16,
				32,
				96,
			),
			'apple'   => array(
				57,
				60,
				72,
				76,
				114,
				120,
				152,
				180,
			),
			'android' => array(
				192,
				512,
			),
		);
		foreach ( $icons as $type => $sizes ) {
			foreach ( $sizes as $size ) {
				$s    = sprintf( '%1$dx%1$d', $size );
				$mask = $file = '';
				switch ( $type ) {
					case 'icon':
						$file = sprintf( 'favicon-%s', $s );
						$mask = '<link rel="icon" type="image/png" sizes="%1$s" href="%2$s" />';
						break;
					case 'apple':
						$file = sprintf( 'apple-icon-%s', $s );
						$mask = '<link rel="apple-touch-icon" sizes="%1$s" href="%2$s" />';
						break;
					case 'android':
						$file = sprintf( 'android-icon-%s', $s );
						$mask = '<link rel="apple-touch-icon" sizes="%1$s" href="%2$s" />';
						break;
				}
				if ( ! empty( $mask ) && ! empty( $file ) ) {
					$meta_tags[] = sprintf( $mask, $s, $this->get_favicon_url( $file ) );
				}
			}
		}
		$meta_tags[] = sprintf(
			'<link rel="shortcut icon" href="%s" />',
			$this->get_favicon_url( 'favicon', 'ico' )
		);
		$meta_tags[] = sprintf(
			'<link rel="mask-icon" href="%s" color="#5bbad5" />',
			$this->get_favicon_url( 'safari-pinned-tab', 'svg' )
		);
		$meta_tags[] = sprintf( '<meta name="msapplication-TileColor" content="%s">', esc_attr( $this->color_title ) );
		$meta_tags[] = sprintf( '<meta name="theme-color" content="%s" />', esc_attr( $this->color_theme ) );
		$meta_tags[] = sprintf(
			'<meta name="msapplication-TileImage" content="%s" />',
			$this->get_favicon_url( 'ms-icon-144x144' )
		);
		/**
		 * manifest.json
		 */
		if ( ! class_exists( 'iWorks_PWA' ) ) {
			$meta_tags[] = '<link rel="manifest" href="/manifest.json" />';
		}
		return $meta_tags;
	}

	/**
	 * Handle "/favicon.json" request.
	 *
	 * @since 1.0.0
	 */
	public function request_favicon() {
		if (
			! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		if ( '/favicon.ico' !== $_SERVER['REQUEST_URI'] ) {
			return;
		}
		header( 'Location: ' . $this->get_favicon_url( 'favicon', 'ico' ) );
		exit;
	}

	/**
	 * Handle "/browserconfig.xml" request.
	 *
	 * @since 1.0.0
	 */
	public function browserconfig_xml() {
		if (
			! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		if ( '/browserconfig.xml' !== $_SERVER['REQUEST_URI'] ) {
			return;
		}
		header( 'Content-type: text/xml' );
		echo '<' . '?xml version="1.0" encoding="utf-8"?' . '>';
		echo PHP_EOL;
		echo '<browserconfig>';
		echo '<msapplication>';
		echo '<tile>';
		$sizes = array( 70, 150, 310 );
		foreach ( $sizes as $size ) {
			$url = $this->get_asset_url(
				sprintf(
					'icons/favicon/ms-icon-%1$dx%1$d.png',
					$size
				)
			);
			printf( '<square%1$dx%1$dlogo src="%2$s"/>', $size, esc_url( $url ) );
		}
		printf( '<TileColor>%s</TileColor>', $this->color_title );
		echo '</tile>';
		echo '</msapplication>';
		echo '</browserconfig>';
		exit;
	}

	private function manifest_json_data() {
		return array(
			'name'             => get_bloginfo( 'sitename' ),
			'short_name'       => $this->short_name,
			'theme_color'      => $this->color_theme,
			'background_color' => $this->color_background,
			'display'          => 'standalone',
			'Scope'            => '/',
			'start_url'        => '/',
			'icons'            => array(
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-36x36.png' ) ),
					'sizes'   => '36x36',
					'type'    => 'image/png',
					'density' => '0.75',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-48x48.png' ) ),
					'sizes'   => '48x48',
					'type'    => 'image/png',
					'density' => '1.0',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-72x72.png' ) ),
					'sizes'   => '72x72',
					'type'    => 'image/png',
					'density' => '1.5',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-96x96.png' ) ),
					'sizes'   => '96x96',
					'type'    => 'image/png',
					'density' => '2.0',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-144x144.png' ) ),
					'sizes'   => '144x144',
					'type'    => 'image/png',
					'density' => '3.0',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-192x192.png' ) ),
					'sizes'   => '192x192',
					'type'    => 'image/png',
					'density' => '4.0',
				),
				array(
					'src'   => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-512x512.png' ) ),
					'sizes' => '512x512',
					'type'  => 'image/png',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/maskable.png' ) ),
					'sizes'   => '1024x1024',
					'type'    => 'image/png',
					'purpose' => 'any maskable',
				),
			),
			'splash_pages'     => null,
		);
	}

	/**
	 * Handle "/manifest.json" request.
	 *
	 * @since 1.0.0
	 */
	public function manifest_json() {
		if (
			! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		if ( '/manifest.json' !== $_SERVER['REQUEST_URI'] ) {
			return;
		}
		header( 'Content-Type: application/json' );
		echo json_encode( $this->manifest_json_data() );
		exit;
	}

	/**
	 * Add dns-prefetch
	 *
	 * @since 1.0.0
	 */
	public function resource_hints( $urls, $relation_types ) {
		if ( 'dns-prefetch' === $relation_types ) {
			// $urls[] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js?ver=1.12.4';
		}
		return $urls;
	}

	/**
	 * Get assets URL
	 *
	 * @since 1.0.0
	 *
	 * @param string $file File name.
	 * @param string $group Group, default "images".
	 *
	 * @return string URL into asset.
	 */
	private function get_asset_url( $file, $group = 'images' ) {
		$url = sprintf(
			'%s/assets/%s/%s',
			$this->url,
			$group,
			$file
		);
		return esc_url( $url );
	}

	public function cache_clear() {
		foreach ( $this->cache_keys as $cache_key ) {
			wp_cache_delete( $cache_key );
		}
	}

	public function get_last_update_string( $content ) {
		$content = wp_cache_get( $this->cache_keys[ __FUNCTION__ ] );
		if ( ! empty( $content ) ) {
			return $content;
		}
		global $wpdb;
		$query = sprintf(
			'select post_date from %s where post_status = %%s',
			$wpdb->posts
		);
		$value = $wpdb->get_var( $wpdb->prepare( $query, 'publish' ) );
		if ( empty( $value ) ) {
			return esc_html__( 'unknown', '5o5-results-archive' );
		}
		$value = strtotime( $value );
		$value = date_i18n( get_option( 'date_format' ), $value );

		$content = sprintf(
			esc_html__( 'Last update: %s', '5o5-results-archive' ),
			$value
		);
		wp_cache_set( $this->cache_keys[ __FUNCTION__ ], $content );
		return $content;
	}

	public function get_last_results_html( $content ) {
		$content    = '<div class="last-results">';
		$categories = array(
			'world'       => array(
				'posts_per_page' => 10,
				'show_more'      => true,
				'group_by_year'  => false,
			),
			'continental' => array(
				'posts_per_page' => 5,
				'show_more'      => true,
			),
			'national'    => array(
				'posts_per_page' => 5,
				'show_more'      => true,
				'show_flag'      => true,
				'show_english'   => true,
			),
			'::last'      => array(
				'posts_per_page' => 12,
				'group_by_year'  => false,
			),
		);
		foreach ( $categories as $slug => $options ) {
			$content .= apply_filters( 'iworks_fleet_result_serie_regatta_list', '', $slug, $options );
		}
		$content .= '</div>';
		return $content;
	}

	public function document_title_parts( $title ) {
		$new = array();
		foreach ( $title as $part ) {
			$new[] = str_replace( '&lt;br&gt;', ' ', $part );
		}
		return $new;
	}
}

