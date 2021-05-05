<?php
$year         = intval( get_post_meta( get_the_ID(), 'iworks_fleet_boat_build_year', true ) );
$year_classes = array(
	'build-year',
);
if ( 1 > $year ) {
	$year           = '&mdash;';
	$year_classes[] = 'unknown-year';
}
?>
<tr <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<td><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo apply_filters( 'iworks_fleet_boat_get_flag', get_the_title(), get_the_ID() ); ?></a></td>
	<td class="<?php echo esc_attr( implode( ' ', $year_classes ) ); ?>"><?php echo $year; ?></td>
	<td><?php echo apply_filters( 'iworks_fleet_boat_get_last_owner', '', get_the_ID() ); ?></td>
</tr>
