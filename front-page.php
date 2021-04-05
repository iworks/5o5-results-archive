<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package 5o5-results-archive
 */

get_header();
?>
<main id="primary" class="site-main">
	<header class="entry-header">
		<h1 class="page-title screen-reader-text"><?php esc_html_e( 'Results', '5o5-results-archive' ); ?></h1>
	</header><!-- .entry-header -->
	<div class="entry-content">
<?php echo apply_filters( 'int505_archive_last_results', '' ); ?>
<?php echo do_shortcode( '[fleet_regattas_list_years]' ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
</main><!-- #main -->
<?php
get_footer();
