<tr <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<td><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo apply_filters( 'iworks_fleet_boat_get_flag', get_the_title(), get_the_ID() ); ?></a></td>
	<td><?php echo apply_filters( 'iworks_fleet_boat_get_hull', '', get_the_ID() ); ?></td>
	<td></td>
</tr>
