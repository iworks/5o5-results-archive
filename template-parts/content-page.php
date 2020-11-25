<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package opi-jobs
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php echo apply_filters( 'int505_archive_the_title', get_the_title(), get_the_ID() ); ?></h1>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>

