<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package opi-jobs
 */

get_header();
?>

	<main id="primary" class="site-main">
<ul class="test">
<li class="bg-color_primary"></li>
<li class="bg-color_accent"></li>
<li class="bg-color_alert"></li>
<li class="bg-color_01"></li>
<li class="bg-color_02"></li>
<li class="bg-color_gray_1"></li>
<li class="bg-color_gray_2"></li>
<li class="bg-color_gray_3"></li>
</ul>


		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'opi-jobs' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'opi-jobs' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
