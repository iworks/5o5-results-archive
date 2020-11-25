<?php

require_once 'class-int5o5-archive.php';

class OPI_Jobs_Theme extends OPI_Jobs {

	/**
	 * Configuration for:
	 * /manifest.json
	 * /browserconfig.xml
	 *
	 * @since 1.0.0
	 */
	private $color_title      = '#2d2683';
	private $color_theme      = '#ffffff';
	private $color_background = '#ffffff';
	private $short_name       = 'Baza Ogłoszeń';

	public function __construct() {
		parent::__construct();
		/**
		 * login class
		 */
		if ( $this->is_wp_login() ) {
			include_once 'class-int5o5-archive-login.php';
			new OPI_Jobs_Login;
		}
		/**
		 * Cookie Class
		 */
		if ( ! is_admin() ) {
			include_once 'class-int5o5-archive-cookie-notice.php';
			new OPI_Jobs_Cookie_Notice;
		}
		/**
		 * hooks
		 */
		add_action( 'wp_head', [ $this, 'html_head' ], PHP_INT_MAX );
		add_filter( 'get_site_icon_url', [ $this, 'get_site_default_icon_url' ], 10, 3 );
		add_filter( 'site_icon_meta_tags', [ $this, 'site_icon_meta_tags' ] );
		add_action( 'parse_request', [ $this, 'manifest_json' ] );
		add_action( 'parse_request', [ $this, 'browserconfig_xml' ] );
		add_action( 'parse_request', [ $this, 'request_favicon' ] );
		add_action( 'init', [ $this, 'register_scripts' ] );
		add_filter( 'login_headertext', [ $this, 'login_headertext' ] );
		/**
		 * Add ID
		 */
		add_filter( 'login_url', [ $this, 'add_id_to_url' ] );
		add_filter( 'register_url', [ $this, 'add_id_to_url' ] );
		add_filter( 'lostpassword_url', [ $this, 'add_id_to_url' ] );
		add_action( 'register_form', [ $this, 'add_id_to_form' ] );
		add_action( 'lostpassword_form', [ $this, 'add_id_to_form' ] );
		add_action( 'resetpass_form', [ $this, 'add_id_to_form' ] );
		add_action( 'login_form', [ $this, 'add_id_to_form' ] );
		/**
		 * speed
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_scripts' ], PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_styles' ], PHP_INT_MAX );
		add_action( 'wp_footer', [ $this, 'dequeue_styles' ], PHP_INT_MAX ); // WPML maybe_late_enqueue_template()
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'wp_resource_hints', [ $this, 'resource_hints' ], 10, 2 );
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
	}

	public function add_id_to_url( $url ) {
		$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $id ) ) {
			$url = add_query_arg( 'id', $id, $url );
		}
		return $url;
	}

	/**
	 * Add hidden input on login related forms
	 */
	public function add_id_to_form() {
		$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		if ( empty( $id ) ) {
			$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		}
		$id = intval( $id );
		if ( 1 > $id ) {
			return;
		}
		printf( '<input type="hidden" name="id" value="%d" />', $id );
	}

	public function login_headertext( $text ) {
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$id     = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

		switch ( $action ) {
			case '':
				$text = esc_html( _x( 'Login', 'login form title', 'opi-jobs' ) );
				break;
			case 'register':
				$text = esc_html( _x( 'Register', 'login form title', 'opi-jobs' ) );
				break;
			case 'lostpassword':
				$text = esc_html( _x( 'Lost Password', 'login form title', 'opi-jobs' ) );
				break;

		}
		if ( 0 < $id && get_option( 'job_manager_submit_job_form_page_id', true ) === $id ) {
			return sprintf(
				'%s<span class="delimiter"> &ndash; </span><span>%s</span>',
				esc_html__( 'Submit Listing', 'opi-jobs' ),
				$text
			);
		}
		return $text;
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'opi-jobs-style', get_stylesheet_uri(), [ 'select2' ], $this->version );
		wp_style_add_data( 'opi-jobs-style', 'rtl', 'replace' );
		wp_enqueue_script( 'opi-jobs' );
	}

	public function register_scripts() {
		/**
		 * select2
		 */
		$file = $this->url . '/assets/externals/select2/css/select2.min.css';
		wp_register_style( 'select2', $file, false, '4.0.3' );
		$file = $this->url . '/assets/externals/select2/js/select2.full.min.js';
		wp_register_script( 'select2', $file, array( 'jquery' ), '4.0.3' );
		/**
		 * theme
		 */
		wp_register_script(
			'opi-jobs',
			$this->url . sprintf( '/assets/scripts/frontend.%sjs', $this->debug ? '' : 'min.' ),
			[ 'jquery', 'select2' ],
			$this->version,
			true
		);
		$data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		$data = apply_filters( 'wp_localize_script_int505_archive_theme', $data );
		wp_localize_script( 'opi-jobs', 'int505_archive_theme', $data );
		/**
		 * datepicker
		 */
		$file = $this->url . '/assets/externals/datepicker/css/jquery-ui-datepicker.css';
		wp_register_style( 'jquery-ui-datepicker', $file, false, '1.12.1' );
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
		$meta_tags[] = '<link rel="manifest" href="/manifest.json" />';
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
		echo '<?xml version="1.0" encoding="utf-8"?>';
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
		$data = array(
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
			),
			'splash_pages'     => null,
		);
		header( 'Content-Type: application/json' );
		echo json_encode( $data );
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

}

