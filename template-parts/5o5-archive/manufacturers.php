<aside class="manufacturers">
	<h3><?php esc_html_e( 'Manufacturers', '5o5-results-archive' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Only with 10 or more hulls.', '5o5-results-archive' ); ?></p>
	<ul>
<?php
$terms = get_terms( 'iworks_fleet_boat_manufacturer' );
foreach ( $terms as $term ) {
	if ( 10 > $term->count ) {
		continue;
	}
	printf(
		'<li%3$s><a href="%1$s">%2$s</a></li>',
		esc_url( get_term_link( $term, 'iworks_fleet_boat_manufacturer' ) ),
		esc_html( $term->name ),
		is_tax( 'iworks_fleet_boat_manufacturer', $term ) ? ' class="current"' : ''
	);
}
?>
	</ul>
</aside>
