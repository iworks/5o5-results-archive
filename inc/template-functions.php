<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package 5o5-results-archive
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function int505_archive_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'int505_archive_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function int505_archive_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'int505_archive_pingback_header' );

function int505_archive_header_button( $option_name, $class ) {
	$id = get_option( $option_name, false );
	if ( empty( $id ) ) {
		return;
	}
	$classes = [
		'button',
		sprintf( 'button-%s', $class ),
	];
	if ( is_page( $id ) ) {
		$classes[] = 'current-page';
	}
	printf(
		'<a class="%s" href="%s">%s</a>',
		esc_attr( implode( ' ', $classes ) ),
		get_permalink( $id ),
		get_the_title( $id )
	);
}

function int505_archive_header_link( $option_name, $class, $link = null ) {
	$id = get_option( $option_name, false );
	if ( empty( $id ) ) {
		return;
	}
	$classes = [
		'link',
		sprintf( 'link-%s', $class ),
	];
	if ( is_page( $id ) ) {
		$classes[] = 'current-page';
	}
	printf(
		'<a class="%s" href="%s">%s</a>',
		esc_attr( implode( ' ', $classes ) ),
		get_permalink( $id ),
		empty( $link ) ? get_the_title( $id ) : sprintf( '<span>%s</span>', $link )
	);
}

/**
 * HTML select
 */
function int505_archive_select( $name, $value = '', $args = array() ) {
	/**
	 * default value
	 */
	if ( isset( $args['default'] ) ) {
		if ( empty( $value ) ) {
			$value = $args['default'];
		}
	}
	/**
	 * options
	 */
	$options = array();
	if ( isset( $args['options'] ) ) {
		$options = $args['options'];
	}
	if ( empty( $options ) && ! empty( $value ) ) {
		$options[ $value['value'] ] = $value['label'];
	}
	$value_to_check = is_array( $value ) && isset( $value['value'] ) ? $value['value'] : $value;
	$content        = sprintf(
		'<select name="%s">',
		esc_attr( $name )
	);
	foreach ( $options as $val => $label ) {
		$content .= sprintf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $val ),
			selected( $val, $value_to_check, false ),
			esc_html( $label )
		);
	}
	$content .= '</select>';
	return $content;
}
