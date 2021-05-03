<?php
$date_start  = get_post_meta( get_the_ID(), 'iworks_fleet_result_date_start', true );
$date_format = 'd M';
add_filter( 'iworks_fleet_result_skip_year_in_title', '__return_true' );
?>
<tr <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<td><?php the_title( '<a href="' . esc_url( get_permalink() ) . '">', '</a>' ); ?></td>
	<td class="year">
	<?php
	if ( ! empty( $date_start ) ) {
		echo date( 'Y', $date_start );
	}
	?>
	</td>
	<td class="date">
	<?php
	if ( ! empty( $date_start ) ) {
		echo date_i18n( $date_format, $date_start );
	}
	?>
	</td>
	<td class="date">
	<?php
	$date = get_post_meta( get_the_ID(), 'iworks_fleet_result_date_end', true );
	if ( ! empty( $date ) ) {
		echo date_i18n( $date_format, $date );
	}
	?>
	</td>
	<td class="race"><?php echo get_post_meta( get_the_ID(), 'iworks_fleet_result_number_of_races', true ); ?></td>
	<td class="place"><?php echo get_post_meta( get_the_ID(), 'iworks_fleet_result_number_of_competitors', true ); ?></td>
</tr>
<?php
remove_filter( 'iworks_fleet_result_skip_year_in_title', '__return_true' );

