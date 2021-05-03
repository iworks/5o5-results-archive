<tr <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<td class="person"><?php the_title( '<a href="' . esc_url( get_permalink() ) . '">', '</a>' ); ?></td>
	<td class="boats"><?php echo apply_filters( 'iworks_fleet_boat_get_by_owner_id', '', get_the_ID(), array( 'show_title' => false ) ); ?></td>
	<td class="last-known-start"><?php echo apply_filters( 'iworks_fleet_result_sailor_last_regatta', '', get_the_ID() ); ?></td>
</tr>
