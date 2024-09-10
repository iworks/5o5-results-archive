<?php

$links = get_post_meta(get_the_ID(), '_links', true);
if ( is_array( $links ) ) {
	$data = array();
	foreach( $links as $one ) {
		if ( isset( $one['url'] ) ) {
			if ( isset( $one['title'] ) ) {
				$data[] = sprintf(
					'<a href="%s" target="%s">%s</a>',
					esc_url( $one['url'] ),
					esc_attr( md5( implode( $one ) ) ),
					esc_html( $one['title'] )
				);
			} else {
				$data[] = sprintf(
					'<a href="%s" target="%s">%s</a>',
					esc_url( $one['url'] ),
					esc_attr( md5( implode( $one ) )),
					esc_html( $one['url'] )
				);
			}
		}
	}
	if ( ! empty( $data ) ) {
		echo '<aside class="fleet-results-links">';
		printf( '<h2>%s</h2>', esc_html__( 'Read or see on other websites', '5o5-results-archive' ) );
		echo '<ul>';
		echo '<li>';
		print( implode( '</li><li>', $data ) );
		echo '</li>';
		echo '</ul>';
		echo '</aside>';
	}
}

