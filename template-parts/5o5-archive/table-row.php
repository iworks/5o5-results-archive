<tr <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<td><?php the_title( '<a href="' . esc_url( get_permalink() ) . '">', '</a>' ); ?></td>
</tr>
