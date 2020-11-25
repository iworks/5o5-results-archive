<?php

require_once 'class-int5o5-archive.php';

class Int5o5_Archive_Login extends Int5o5_Archive {
	public function __construct() {
		parent::__construct();
		// add_action( 'signup_header', array( $this, 'output' ), 99 );
		// add_action( 'login_head', array( $this, 'output' ), 99 );
		add_filter( 'login_headerurl', array( $this, 'header_url' ) );
		add_filter( 'login_headertext', array( $this, 'header_text' ) );
		// add_filter( 'wp_login_errors', array( $this, 'wp_login_errors' ) );
		// add_filter( 'wp_login_errors', array( $this, 'set_remember_me' ) );
		// add_filter( 'gettext', array( $this, 'gettext_login_form_labels' ), 20, 3 );
		// add_filter( 'logout_redirect', array( $this, 'logout_redirect' ), 99 );
		// add_filter( 'login_redirect', array( $this, 'login_redirect' ), 99 );
		add_action( 'login_header', [ $this, 'header' ] );
		add_action( 'login_footer', [ $this, 'footer' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'enqueue' ] );
		add_filter( 'login_link_separator', '__return_empty_string' );
		add_filter( 'register', [ $this, 'register' ] );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( '5o5-results-archive-style', get_stylesheet_uri(), array(), $this->version );
	}

	public function header() {
		echo '<div id="page" class="site">';
		get_template_part( 'template-parts/site', 'header' );
	}

	public function footer() {
		get_template_part( 'template-parts/site', 'footer' );
		echo '</div>';
	}

	public function header_text( $content ) {
		return get_bloginfo( 'name' );
	}

	public function header_url( $content ) {
		return home_url();
	}


	public function register( $register ) {
		return preg_replace(
			'/>[^<]+</',
			sprintf(
				'>%s<',
				esc_html__( 'You do not have an account - register', '5o5-results-archive' )
			),
			$register
		);
	}
}

