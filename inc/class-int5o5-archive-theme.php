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
		add_action( 'parse_request', array( $this, 'request_favicon' ) );
		add_action( 'init', array( $this, 'register_scripts' ) );
		add_action( 'save_post', array( $this, 'cache_clear' ) );
		add_filter( 'document_title_parts', array( $this, 'document_title_parts' ) );
		add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ) );
		/**
		 * PWA
		 */
		add_filter( 'iworks_pwa_offline_svg', array( $this, 'iworks_pwa_offline_svg' ) );
		add_filter( 'iworks_pwa_offline_file', array( $this, 'iworks_pwa_offline_file' ) );
		add_filter( 'iworks_pwa_offline_urls_set', array( $this, 'iworks_pwa_offline_urls_set' ) );
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
		/**
		 * Integration: OG — Better Share on Social Media
		 */
		add_filter( 'og_og_image_value', array( $this, 'filter_og_og_image_value_for_single_person' ) );
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
			'select post_date from %s where post_status = %%s order by 1 desc limit 1',
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

	// This theme uses wp_nav_menu() in one location.
	public function register_nav_menus() {
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', '5o5-results-archive' ),
			)
		);
		register_nav_menus(
			array(
				'footer' => esc_html__( 'Footer', '5o5-results-archive' ),
			)
		);
	}

	public function filter_og_og_image_value_for_single_person( $value ) {
		if ( is_singular( 'iworks_fleet_person' ) ) {
			$hash = get_post_meta( get_the_ID(), 'iworks_fleet_contact_gravatar', true );
			if ( ! empty( $hash ) ) {
				return add_query_arg(
					array(
						's' => 512,
						'd' => 'mm',
						'r' => 'g',
					),
					'https://gravatar.com/avatar/' . $hash
				);
			}
		}
		return $value;
	}
}

